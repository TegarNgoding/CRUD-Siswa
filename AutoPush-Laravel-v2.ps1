# AutoPush-Laravel-v2.ps1
# Laravel/GitHub auto-push for Windows. Checks every 120 seconds.

$ErrorActionPreference = "Continue"
$RepoPath = Split-Path -Parent $MyInvocation.MyCommand.Path
$IntervalSeconds = 120

function Invoke-Git {
    param([string[]]$GitArgs)
    $out = @(& git -C $RepoPath @GitArgs 2>&1)
    $code = $LASTEXITCODE
    [pscustomobject]@{
        ExitCode = $code
        Output = (($out | ForEach-Object { "$_" }) -join "`n").Trim()
    }
}

function Write-Log {
    param([string]$Message)
    $line = "[{0}] {1}" -f (Get-Date -Format "yyyy-MM-dd HH:mm:ss"), $Message
    Write-Host $line
    try { Add-Content -LiteralPath $LogPath -Value $line -Encoding UTF8 } catch {}
}

function Test-SensitivePath {
    param([string]$Path)
    $p = $Path -replace '\\','/'
    return (
        $p -match '(^|/)\.env$' -or
        $p -match '(^|/)\.env\.(?!example$|testing\.example$)' -or
        $p -match '(^|/)(id_rsa|id_ed25519|id_ecdsa|private_key)(\..*)?$' -or
        $p -match '\.(pem|p12|pfx|key)$' -or
        $p -match '(^|/)(credentials|service-account)(\.|/|$)'
    )
}

if (-not (Get-Command git -ErrorAction SilentlyContinue)) {
    Write-Host "Git tidak ditemukan di PATH. Install Git for Windows atau perbaiki PATH."
    Read-Host "Tekan Enter untuk keluar"
    exit 1
}

$root = Invoke-Git @("rev-parse", "--show-toplevel")
if ($root.ExitCode -ne 0) {
    Write-Host "File ini harus berada di root repository Git."
    Write-Host $root.Output
    Read-Host "Tekan Enter untuk keluar"
    exit 1
}
$RepoPath = $root.Output.Trim()

$gd = Invoke-Git @("rev-parse", "--git-dir")
$gitDir = $gd.Output
if (-not [IO.Path]::IsPathRooted($gitDir)) { $gitDir = Join-Path $RepoPath $gitDir }
$LogPath = Join-Path $gitDir "auto-push-v2.log"

Write-Log "Auto-push v2 dimulai. Repository: $RepoPath"
Write-Log "Pemeriksaan setiap $IntervalSeconds detik. Tutup jendela ini untuk berhenti."

while ($true) {
    try {
        $branch = Invoke-Git @("branch", "--show-current")
        if ($branch.ExitCode -ne 0 -or !$branch.Output) {
            Write-Log "DITUNDA: branch tidak terdeteksi atau HEAD detached."
            Start-Sleep -Seconds $IntervalSeconds
            continue
        }

        $upstream = Invoke-Git @("rev-parse", "--abbrev-ref", "--symbolic-full-name", "@{u}")
        if ($upstream.ExitCode -ne 0 -or !$upstream.Output) {
            Write-Log "DITUNDA: branch belum punya upstream. Jalankan git push -u origin $($branch.Output.Trim()) sekali."
            Start-Sleep -Seconds $IntervalSeconds
            continue
        }
        $upstreamName = $upstream.Output.Trim()

        # Refuse to auto-commit if sensitive files are already tracked.
        $tracked = Invoke-Git @("ls-files")
        $trackedPaths = @($tracked.Output -split "`n" | Where-Object { $_ })
        $trackedSecrets = @($trackedPaths | Where-Object { Test-SensitivePath $_ })
        if ($trackedSecrets.Count -gt 0) {
            Write-Log "AMAN-STOP: file sensitif sudah dilacak Git: $($trackedSecrets -join ', '). Hentikan tracking dan periksa repository."
            Start-Sleep -Seconds $IntervalSeconds
            continue
        }

        $add = Invoke-Git @("add", "-A")
        if ($add.ExitCode -ne 0) {
            Write-Log "GIT ADD GAGAL (exit $($add.ExitCode)): $($add.Output)"
            Start-Sleep -Seconds $IntervalSeconds
            continue
        }
        if ($add.Output -match 'warning:') {
            Write-Log "PERINGATAN git add (bukan otomatis error): $($add.Output)"
        }

        $staged = Invoke-Git @("diff", "--cached", "--name-only", "--diff-filter=ACMR")
        $stagedPaths = @($staged.Output -split "`n" | Where-Object { $_ })
        $secretStaged = @($stagedPaths | Where-Object { Test-SensitivePath $_ })
        foreach ($path in $secretStaged) {
            $null = Invoke-Git @("reset", "--quiet", "--", $path)
        }
        if ($secretStaged.Count -gt 0) {
            Write-Log "AMAN-STOP: file sensitif dikeluarkan dari staging: $($secretStaged -join ', '). Periksa .gitignore."
        }

        $diffCached = Invoke-Git @("diff", "--cached", "--quiet")
        if ($diffCached.ExitCode -eq 1) {
            $message = "Auto-update: " + (Get-Date -Format "yyyy-MM-dd HH:mm:ss")
            $commit = Invoke-Git @("commit", "-m", $message)
            if ($commit.ExitCode -eq 0) {
                Write-Log "COMMIT BERHASIL: $message"
                if ($commit.Output) { Write-Log $commit.Output }
            } else {
                Write-Log "COMMIT GAGAL (exit $($commit.ExitCode)): $($commit.Output)"
            }
        } elseif ($diffCached.ExitCode -eq 0) {
            Write-Log "Tidak ada perubahan baru untuk di-commit."
        } else {
            Write-Log "GAGAL memeriksa staging: $($diffCached.Output)"
        }

        # If push failed earlier, this retries the existing commits without making duplicates.
        $aheadResult = Invoke-Git @("rev-list", "--count", "$upstreamName..HEAD")
        if ($aheadResult.ExitCode -eq 0) {
            $ahead = 0
            [void][int]::TryParse($aheadResult.Output.Trim(), [ref]$ahead)
            if ($ahead -gt 0) {
                $push = Invoke-Git @("push")
                if ($push.ExitCode -eq 0) {
                    Write-Log "PUSH BERHASIL ke $upstreamName."
                    if ($push.Output) { Write-Log $push.Output }
                } else {
                    Write-Log "PUSH GAGAL (exit $($push.ExitCode)); akan dicoba lagi pada pemeriksaan berikutnya."
                    if ($push.Output) { Write-Log $push.Output }
                }
            }
        } else {
            Write-Log "GAGAL memeriksa commit yang belum dikirim: $($aheadResult.Output)"
        }
    } catch {
        Write-Log "ERROR: $($_.Exception.Message)"
    }

    Start-Sleep -Seconds $IntervalSeconds
}
