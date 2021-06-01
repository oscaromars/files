#!/bin/bash

BASE_URL='/var/www/html'


#SETEANDO LOS PERMISOS CORRECTOS AL PROYECTO (755 PARA DIRECTORIOS Y 644 PARA ARCHIVOS)
find $BASE_URL/asgard/ -type d -exec chmod 0755 {} \;
find $BASE_URL/asgard/ -type f -exec chmod 0644 {} \;

#CAMBIANDO PROPIETARIO DE CARPETAS QUE APACHE NECESITA ESCRIBIR (LAS DEMAS CARPETAS Y ARCHIVOS DEBEN ESTAR COMO root:root);
chown -R apache:apache $BASE_URL/asgard/runtime
chown -R apache:apache $BASE_URL/asgard/uploads
chown -R apache:apache $BASE_URL/asgard/web/assets
chown -R apache:apache $BASE_URL/asgard/vendor/mpdf/mpdf/tmp

#BORRADO DE CACHE
rm -rf $BASE_URL/asgard/web/assets/*
rm -rf $BASE_URL/asgard/composer.lock

#BORRADO DE ARCHIVO LOGS DEL PROYECTO
rm -rf $BASE_URL/asgard/runtime/logs/*

#BORRANDO ARCHIVOS DE PRUEBAS UNITARIAS
rm -rf $BASE_URL/asgard/codeception.yml
rm -rf $BASE_URL/asgard/tests

#BORRADO DE ARCHIVO NO NECESARIOS DEL PROYECTO
rm -rf $BASE_URL/asgard/*.md
rm -rf $BASE_URL/asgard/commands
rm -rf $BASE_URL/asgard/nbproject

#BORRADO DE TODA CARPETA CON EXTENSION .GIT
find $BASE_URL/asgard/ -name .gitignore -exec rm -rf {} \;
find $BASE_URL/asgard/ -name .git -exec rm -rf {} \;
