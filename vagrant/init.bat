@echo off

:: if the init.bat is not working change the following line to:
:: set homesteadRoot=PATH_TO_YOUR_VAGRANT_DIRECTORY\vagrant_config
:: z.B. set homesteadRoot=C:\Users\Joe\Desktop\vagrant\vagrant_config

set homesteadRoot=%cd%\vagrant_config

mkdir "%homesteadRoot%"

copy /-y src\stubs\Homestead.yaml "%homesteadRoot%\Homestead.yaml"
copy /-y src\stubs\after.sh "%homesteadRoot%\after.sh"
copy /-y src\stubs\aliases "%homesteadRoot%\aliases"

set homesteadRoot=
echo Homestead initialized!
