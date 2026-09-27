@echo off
cd /d "C:\laragon\www\allbirds-store"
"C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe" artisan serve --host=0.0.0.0 --port=8888
pause
