@ECHO OFF
CD %~dp0
CD ..

SET DEVFLAG=--no-dev
SET PREFLAG=
SET OPTFLAG=

FOR %%F IN (%*) DO (
    IF "%%~F"=="--dev"                  SET DEVFLAG=--dev
    IF "%%~F"=="--no-dev"               SET DEVFLAG=--no-dev
    IF "%%~F"=="--prefer-dist"          SET PREFLAG=--prefer-dist
    IF "%%~F"=="--prefer-source"        SET PREFLAG=--prefer-source
    IF "%%~F"=="-o"                     SET OPTFLAG=--optimize-autoloader
    IF "%%~F"=="--optimize-autoloader"  SET OPTFLAG=--optimize-autoloader
)

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
ECHO Composer update module installer
ECHO --------------------------------
ECHO.

SET FLAGS=%DEVFLAG%
IF NOT "%PREFLAG%"=="" SET FLAGS=%FLAGS% %PREFLAG%
php composer.phar update gridguyz/module-installer %FLAGS%

ECHO.
ECHO Composer update
ECHO ---------------
ECHO.

IF NOT "%OPTFLAG%"=="" SET FLAGS=%FLAGS% %OPTFLAG%
php composer.phar update %FLAGS%

ECHO.
ECHO Maintenance deactivate
ECHO ----------------------
ECHO.

DEL "public\maintenance.php"
