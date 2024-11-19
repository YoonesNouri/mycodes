@echo off
del compiled\*.* /q

echo CSS
java -jar ../css/lib/yuicompressor.jar -o compiled/style.css mobile.css

echo Processs
\wamp\bin\php\php5.3.3\php.exe -n compile.php