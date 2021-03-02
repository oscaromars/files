#!/bin/bash

USER='uteg'
PASS='Utegadmin2016*'
CURRENT_DIR=`pwd`

echo -n "Escriba el password del Usuario Root de Mysql:"
read -s ROOT_PASS
echo ""



#echo -n "Instalar en Produccion (1) o Desarrollo (2):"
#read -s PROD
#echo ""

# CREACION DEL USUARIO MYSQL
# mysql -uroot -p${ROOT_PASS} -e "DROP USER IF EXISTS '${USER}'@'localhost';"
mysql -uroot -p${ROOT_PASS} -e "CREATE USER '${USER}'@'localhost' IDENTIFIED BY '${PASS}';"

#if [ $PROD -eq 1 ]; then
#    echo "INSTALANDO en Produccion......"
#else
#    echo "INSTALANDO en Desarrollo......"
#fi

echo -n "Desea Instalar todas las Bases de Datos YES (1) o NO (2):"
read -s UPDB
echo $UPDB

# DATABASE ASGARD
if [ $UPDB -ne 1 ]; then
    echo -n "Desea Instalar la Base de datos Asgard YES (1) o NO (2):"
    read -s ASG
    echo $ASG
fi
if [ $UPDB -eq 1 ] || [ $ASG -eq 1 ]; then
    echo "SUBIENDO db_asgard......"
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/estructura/db_asgard.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_asgard_data_01.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_asgard_data_02.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_asgard_data_03.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_asgard_data_04_1.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_asgard_data_04_2.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_asgard_data_04_3.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_asgard_data_05.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_asgard_data_06.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_asgard_data_07.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_asgard_data_08.sql
    mysql -uroot -p${ROOT_PASS} -e "GRANT ALL PRIVILEGES ON db_asgard.* TO '${USER}'@'localhost';"
fi

# DATABASE GENERAL
if [ $UPDB -ne 1 ]; then
    echo -n "Desea Instalar la Base de datos General YES (1) o NO (2):"
    read -s GEN
    echo $GEN
fi
if [ $UPDB -eq 1 ] || [ $GEN -eq 1 ]; then
    echo "SUBIENDO db_general......"
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/estructura/db_general.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_general_data.sql
    mysql -uroot -p${ROOT_PASS} -e "GRANT ALL PRIVILEGES ON db_general.* TO '${USER}'@'localhost';"
fi

# DATABASE CRM
if [ $UPDB -ne 1 ]; then
    echo -n "Desea Instalar la Base de datos CRM YES (1) o NO (2):"
    read -s CRM
    echo $CRM
fi
if [ $UPDB -eq 1 ] || [ $CRM -eq 1 ]; then
    echo "SUBIENDO db_crm......"
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/estructura/db_crm.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_crm_data.sql
    mysql -uroot -p${ROOT_PASS} -e "GRANT ALL PRIVILEGES ON db_crm.* TO '${USER}'@'localhost';"
fi

# DATABASE CAPTACION
if [ $UPDB -ne 1 ]; then
    echo -n "Desea Instalar la Base de datos Captacion YES (1) o NO (2):"
    read -s CAP
    echo $CAP
fi
if [ $UPDB -eq 1 ] || [ $CAP -eq 1 ]; then
    echo "SUBIENDO db_captacion......"
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/estructura/db_captacion.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_captacion_data_01.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_captacion_data_02.sql
    mysql -uroot -p${ROOT_PASS} -e "GRANT ALL PRIVILEGES ON db_captacion.* TO '${USER}'@'localhost';"
fi


# DATABASE MAILING
if [ $UPDB -ne 1 ]; then
    echo -n "Desea Instalar la Base de datos para Correo Electronico Masivo  YES (1) o NO (2):"
    read -s EMAI
    echo $EMAI
fi
if [ $UPDB -eq 1 ] || [ $EMAI -eq 1 ]; then
    echo "SUBIENDO db_mailing......"
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/estructura/db_mailing.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_mailing_data.sql
    mysql -uroot -p${ROOT_PASS} -e "GRANT ALL PRIVILEGES ON db_mailing.* TO '${USER}'@'localhost';"
fi



# DATABASE ACADEMICO
if [ $UPDB -ne 1 ]; then
    echo -n "Desea Instalar la Base de datos Academico YES (1) o NO (2):"
    read -s ACA
    echo $ACA
fi
if [ $UPDB -eq 1 ] || [ $ACA -eq 1 ]; then
    echo "SUBIENDO db_academico......"
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/estructura/db_academico.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_academico_data_1.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_academico_data_2.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_academico_data_3.sql
    mysql -uroot -p${ROOT_PASS} -e "GRANT ALL PRIVILEGES ON db_academico.* TO '${USER}'@'localhost';"
fi
# DATABASE FACTURACION
if [ $UPDB -ne 1 ]; then
    echo -n "Desea Instalar la Base de datos Facturacion YES (1) o NO (2):"
    read -s FAC
    echo $FAC
fi
if [ $UPDB -eq 1 ] || [ $FAC -eq 1 ]; then
    echo "SUBIENDO db_facturacion......"
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/estructura/db_facturacion.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_facturacion_data_01.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_facturacion_data_02.sql
    mysql -uroot -p${ROOT_PASS} -e "GRANT ALL PRIVILEGES ON db_facturacion.* TO '${USER}'@'localhost';"
fi

# DATABASE REPOSITORIO
if [ $UPDB -ne 1 ]; then
    echo -n "Desea Instalar la Base de datos Repositorio YES (1) o NO (2):"
    read -s REP
    echo $REP
fi
if [ $UPDB -eq 1 ] || [ $REP -eq 1 ]; then
    echo "SUBIENDO db_repositorio......"
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/estructura/db_repositorio.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_repositorio_data.sql
    mysql -uroot -p${ROOT_PASS} -e "GRANT ALL PRIVILEGES ON db_repositorio.* TO '${USER}'@'localhost';"
fi

# DATABASE MARCACION HISTORICO
if [ $UPDB -ne 1 ]; then
    echo -n "Desea Instalar la Base de datos Marcacion Historico YES (1) o NO (2):"
    read -s REP
    echo $REP
fi
if [ $UPDB -eq 1 ] || [ $REP -eq 1 ]; then
    echo "SUBIENDO db_marcacion_historico......"
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/estructura/db_marcacion_historico.sql
    #mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_marcacion_historico_data.sql
    mysql -uroot -p${ROOT_PASS} -e "GRANT ALL PRIVILEGES ON db_marcacion_historico.* TO '${USER}'@'localhost';"
fi

# DATABASE EXTERNO
if [ $UPDB -ne 1 ]; then
    echo -n "Desea Instalar la Base de datos Externo YES (1) o NO (2):"
    read -s REP
    echo $REP
fi
if [ $UPDB -eq 1 ] || [ $REP -eq 1 ]; then
    echo "SUBIENDO db_externo......"
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/estructura/db_externo.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_externo_data.sql
    mysql -uroot -p${ROOT_PASS} -e "GRANT ALL PRIVILEGES ON db_externo.* TO '${USER}'@'localhost';"
fi

# DATABASE GESTION DOCUMENTAL
if [ $UPDB -ne 1 ]; then
    echo -n "Desea Instalar la Base de datos Documental YES (1) o NO (2):"
    read -s REP
    echo $REP
fi
if [ $UPDB -eq 1 ] || [ $REP -eq 1 ]; then
    echo "SUBIENDO db_documental......"
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/estructura/db_documental.sql
    mysql -u${USER} -p${PASS} < $CURRENT_DIR/base_nueva_prod/data/db_documental_data_1.sql
    mysql -uroot -p${ROOT_PASS} -e "GRANT ALL PRIVILEGES ON db_documental.* TO '${USER}'@'localhost';"
fi

# FLUSH PRIVILEGES

echo "Aplicando Permisos......"
mysql -uroot -p${ROOT_PASS} -e "FLUSH PRIVILEGES;"

echo "Script Finalizado!!! ;)"
