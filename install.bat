@echo off

:: Check if running with administrative privileges
>nul 2>&1 "%SYSTEMROOT%\system32\cacls.exe" "%SYSTEMROOT%\system32\config\system"

:: If not running with administrative privileges, restart script with admin rights
if "%errorlevel%" NEQ "0" (
    echo Requesting administrative privileges...
    echo Set UAC = CreateObject^("Shell.Application"^) > "%temp%\getadmin.vbs"
    echo UAC.ShellExecute "%~s0", "", "", "runas", 1 >> "%temp%\getadmin.vbs"
    "%temp%\getadmin.vbs"
    del "%temp%\getadmin.vbs"
    exit /b
)

:: Continue with administrative privileges
set "folderName=SecretSphere"
set "installPath=C:\Program Files\%folderName%"

echo Creating folder: %installPath%
mkdir "%installPath%"

if not exist "%installPath%" (
    echo Failed to create the folder.
    pause
    exit /b 1
)

echo Folder created successfully.

echo Copying contents to the folder...
xcopy /Y /E /I "%~dp0*" "%installPath%"

echo All contents copied successfully.

echo Copying .lnk files from the desktop...
xcopy /Y /C /F  "%installPath%\*.lnk" "%USERPROFILE%\Desktop\"

echo .lnk files copied successfully.

pause
