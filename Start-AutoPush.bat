@echo off
setlocal
cd /d "%~dp0"
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0AutoPush-Laravel.ps1"
echo.
echo Auto-push berhenti.
pause
