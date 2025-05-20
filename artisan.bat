@echo off

@setlocal

set ARTISAN_PATH=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=php.exe

"%PHP_COMMAND%" "%ARTISAN_PATH%artisan" %*

@endlocal
