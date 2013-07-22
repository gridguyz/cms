@ECHO OFF
CD %~dp0
CD ..

ECHO.
ECHO Maintenance activate
ECHO --------------------
ECHO.

COPY "public\app\maintenance.php" "public\"

ECHO.
ECHO Git pull
ECHO --------
ECHO.

git pull

ECHO.
ECHO Composer update
ECHO ---------------
ECHO.

php composer.phar update --no-dev

ECHO.
ECHO Maintenance deactivate
ECHO ----------------------
ECHO.

DEL "public\maintenance.php"
