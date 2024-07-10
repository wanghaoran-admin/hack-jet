@echo off
REM set PHP_FCGI_CHILDREN=5
set PHP_FCGI_MAX_REQUESTS=1000
echo Starting nginx...
.\RunHiddenConsole.exe nginx.exe
echo Starting PHP FastCGI...
.\RunHiddenConsole.exe .\xxfpm-master\bin\xxfpm.exe ".\php-cgi.exe -c php.ini" -n 5 -i 127.0.0.1 -p 9001
echo Starting WebSite...
start http://127.0.0.1:5050
exit