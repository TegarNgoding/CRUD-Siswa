# AutoPush-Laravel.ps1
# Automatically commits and pushes Laravel project changes every 2 minutes.
# Run this script from inside the Git repository. Compatible with Windows PowerShell 5.1+.

$ErrorActionPreference = "Stop"
$RepoPath = Split-Path -Parent $MyInvocation.MyCommand.Path
$IntervalSeconds = 120
$LogPath = Join-Path $RepoPath ".git\auto-push.log"

function Write-Log {
    param([string]$Message)
    $line = "[{0}] {1}" -f (Get-Date -Format "yyyy-MM-dd HH:mm:ss"), $Message
    Write-Host $line
    Add-Content -LiteralPath $LogPath -Value $line -Encoding UTF8
}

function Get-GitOutput {
    param([string[]]$GitArgs)
    $output = & git -C $RepoPath @GitArgs 2>&1
    $code = $LASTEXITCODE
    if ($code -ne 0) {
        throw ("git {0} gagal (exit {1}): {2}" -f ($GitArgs -join " "), $code, ($output -join "`n"))
    }
    return (($output | ForEach-Object { "$_" }) -join "`n").Trim()
}

function Test-SensitivePath {
    param([string]$Path)
    # Allow Laravel's example env files, but never auto-stage real env files or common private keys.
    if ($Path -match '(^|/)\.env$') { return $true }
    if ($Path -match '(^|/)\.env\.(?!example$|testing\.example$)') { return $true }
    if ($Path -match '(^|/)(id_rsa|id_ed25519|id_ecdsa|private_key)(\..*)?$') { return $true }
    if ($Path -match '\.(pem|p12|pfx|key)$') { return $true }
    if ($Path -match '(^|/)(credentials|service-account)(\.|/|$)') { return $true }
    return $false
}

if (-not (Get-Command git -ErrorAction SilentlyContinue)) {
    Write-Host "Git tidak ditemukan. Install Git for Windows dan pastikan Git tersedia di PATH."
    Read-Host "Tekan Enter untuk keluar"
    exit 1
}

if (-not (Test-Path (Join-Path $RepoPath ".git"))) {
    Write-Host "Folder script ini bukan root repository Git. Simpan file ini di folder utama project Laravel yang memiliki folder .git."
    Read-Host "Tekan Enter untuk keluar"
    exit 1
}

if (-not (Test-Path $LogPath)) {
    New-Item -ItemType File -Path $LogPath -Force | Out-Null
}

Write-Log "Auto-push dimulai. Folder: $RepoPath"
Write-Log "Pemeriksaan dilakukan setiap $IntervalSeconds detik. Tutup jendela ini untuk menghentikan."

while ($true) {
    try {
        # Require a named branch and an upstream. Never guess where to push.
        $branch = Get-GitOutput @("branch", "--show-current")
        if ([string]::IsNullOrWhiteSpace($branch)) {
            Write-Log "BERHENTI SEMENTARA: HEAD detached. Pindah ke branch biasa terlebih dahulu."
            Start-Sleep -Seconds $IntervalSeconds
            continue
        }

        $upstreamOutput = & git -C $RepoPath rev-parse --abbrev-ref --symbolic-full-name '@{u}' 2>&1
        if ($LASTEXITCODE -ne 0 -or [string]::IsNullOrWhiteSpace(($upstreamOutput -join "").Trim())) {
            Write-Log "BERHENTI SEMENTARA: branch '$branch' belum punya upstream. Atur dengan git push -u origin $branch."
            Start-Sleep -Seconds $IntervalSeconds
            continue
        }
        $upstream = ($upstreamOutput -join "").Trim()

        # If remote has commits not contained in local HEAD, do not auto-merge or overwrite anything.
        & git -C $RepoPath merge-base --is-ancestor $upstream HEAD 2>$null
        if ($LASTEXITCODE -ne 0) {
            Write-Log "BERHENTI SEMENTARA: remote '$upstream' punya perubahan yang belum ada di lokal atau branch berbeda. Sinkronkan manual dulu."
            Start-Sleep -Seconds $IntervalSeconds
            continue
        }

        # If a real .env/private key is already tracked, refuse to push automatically.
        $tracked = Get-GitOutput @("ls-files")
        $trackedPaths = @($tracked -split "`n" | Where-Object { -not [string]::IsNullOrWhiteSpace($_) })
        $trackedSecrets = @($trackedPaths | Where-Object { Test-SensitivePath $_ })
        if ($trackedSecrets.Count -gt 0) {
            Write-Log "AMAN-STOP: file sensitif sudah ter-track oleh Git: $($trackedSecrets -join ', '). Periksa repository dan hentikan tracking file rahasia sebelum menjalankan auto-push."
            Start-Sleep -Seconds $IntervalSeconds
            continue
        }

        # Stage changes. Laravel's standard .gitignore should exclude .env, vendor, and generated files.
        $null = Get-GitOutput @("add", "-A")

        # Extra safety: unstage any common secret file accidentally staged.
        $stagedText = Get-GitOutput @("diff", "--cached", "--name-only", "--diff-filter=ACMR")
        $stagedPaths = @($stagedText -split "`n" | Where-Object { -not [string]::IsNullOrWhiteSpace($_) })
        $sensitiveStaged = @($stagedPaths | Where-Object { Test-SensitivePath $_ })
        foreach ($path in $sensitiveStaged) {
            & git -C $RepoPath reset --quiet -- $path
        }
        if ($sensitiveStaged.Count -gt 0) {
            Write-Log "PERINGATAN: file sensitif tidak ikut di-commit: $($sensitiveStaged -join ', '). Perbaiki .gitignore agar peringatan ini tidak berulang."
        }

        $hasStagedChanges = $false
        & git -C $RepoPath diff --cached --quiet
        if ($LASTEXITCODE -eq 1) { $hasStagedChanges = $true }
        elseif ($LASTEXITCODE -ne 0) { throw "Gagal memeriksa staged changes." }

        if ($hasStagedChanges) {
            $message = "Auto-update: " + (Get-Date -Format "yyyy-MM-dd HH:mm:ss")
            $null = Get-GitOutput @("commit", "-m", $message)
            Write-Log "Commit dibuat: $message"
        }

        # Push any local commits, including a retry after a previous network failure.
        $aheadText = Get-GitOutput @("rev-list", "--count", "$upstream..HEAD")
        $ahead = 0
        [void][int]::TryParse($aheadText, [ref]$ahead)
        if ($ahead -gt 0) {
            $pushOutput = & git -C $RepoPath push 2>&1
            $pushCode = $LASTEXITCODE
            if ($pushCode -eq 0) {
                Write-Log "Push berhasil. Branch: $branch"
            } else {
                Write-Log "Push gagal (exit $pushCode). Akan dicoba lagi pada pemeriksaan berikutnya: $($pushOutput -join ' ')"
            }
        } elseif (-not $hasStagedChanges) {
            Write-Log "Tidak ada perubahan baru."
        }
    }
    catch {
        Write-Log "ERROR: $($_.Exception.Message)"
    }

    Start-Sleep -Seconds $IntervalSeconds
}
