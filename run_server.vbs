Set WshShell = CreateObject("WScript.Shell")
WshShell.CurrentDirectory = "C:\laragon\www\allbirds-store"
WshShell.Run """C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe"" artisan serve --host=0.0.0.0 --port=8888", 0, False
