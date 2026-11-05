@echo off
setlocal
cd /d "%~dp0"
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0AutoPush-Laravel-v2.ps1"
echo.
echo Auto-push v2 berhenti.
pause
