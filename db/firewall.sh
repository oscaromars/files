#!/bin/bash

IP_UTEG_EQU="181.39.139.67" # ip publica del equipo
IP_UTEG_DES="181.39.139.68" # ip publica desde otra red para ingresar al servidor
IP_UTEG_DES2="181.39.139.69" # ip publica desde otra red para ingresar al servidor
IP_UTEG_OFI="186.68.143.106" # ip publica desde otra red para ingresar al servidor
RED_PRIVADA="130.107.1.0/24" # Red privada ligada a la segunda interfaz de red del equipo
DNS_SERVER="200.93.192.161 200.93.192.148" # DNS de la ip publica entregado por el proveedor
GATEWAY="181.39.139.65" # GATEWAY entregado por el proveedor
ETH1="em1" # Interfza secundaria del servidor en la cual esta configurada la ip privada
ETH0="em2" # Interfaz primaria del servidor en la cual esta configurada la ip publica

echo "Aplicando Reglas de Firewall..."

## FLUSH de reglas
iptables -F
iptables -X
iptables -Z
iptables -t nat -F

## Establecemos politica por defecto
iptables -P INPUT DROP
iptables -P OUTPUT DROP
iptables -P FORWARD DROP

## Se permite todo para localhost
iptables -A INPUT  -i lo -j ACCEPT
iptables -A OUTPUT -o lo -j ACCEPT

## A nuestras IP le dejamos todo
iptables -A INPUT  -s $IP_UTEG_DES -j ACCEPT
iptables -A OUTPUT -d $IP_UTEG_DES -j ACCEPT
iptables -A INPUT  -s $IP_UTEG_DES2 -j ACCEPT
iptables -A OUTPUT -d $IP_UTEG_DES2 -j ACCEPT
iptables -A INPUT  -s $IP_UTEG_OFI -j ACCEPT
iptables -A OUTPUT -d $IP_UTEG_OFI -j ACCEPT

## Se permite acceso desde la red privada
iptables -A INPUT -i $ETH1 -s $RED_PRIVADA -j ACCEPT
iptables -A OUTPUT -o $ETH1 -d $RED_PRIVADA -j ACCEPT

## El puerto 80 y 433 de www debe estar abierto, es un servidor web.
iptables -A INPUT  -p tcp --dport 80 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -p tcp --sport 80 -m state --state ESTABLISHED -j ACCEPT
iptables -A INPUT  -p tcp --dport 443 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -p tcp --sport 443 -m state --state ESTABLISHED -j ACCEPT

## Salida de Internet
iptables -A OUTPUT -o $ETH0 -p tcp --dport 80 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A INPUT -i $ETH0 -p tcp --sport 80 -m state --state ESTABLISHED -j ACCEPT
iptables -A OUTPUT -o $ETH0 -p tcp --dport 443 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A INPUT -i $ETH0 -p tcp --sport 443 -m state --state ESTABLISHED -j ACCEPT

## Permitir trafico Establecido
iptables -A INPUT -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT
iptables -A OUTPUT -m conntrack --ctstate ESTABLISHED -j ACCEPT

## Drop paquetes invalidos
iptables -A INPUT -m conntrack --ctstate INVALID -j DROP

## Se agrega permisos para Salida puertos mail server
iptables -A INPUT  -p tcp --sport 587 -j ACCEPT
iptables -A OUTPUT -p tcp --dport 587 -j ACCEPT

## Se bloquea los pings entrantes al servidor
iptables -A INPUT -p icmp --icmp-type echo-request -j DROP
iptables -A OUTPUT -p icmp --icmp-type echo-reply -j DROP

## Se Acepta los pings saliente del servidor
iptables -A INPUT -p icmp --icmp-type echo-reply -j ACCEPT
iptables -A OUTPUT -p icmp --icmp-type echo-request -j ACCEPT

## Se acepta conecciones a DNS
# iptables -A OUTPUT -p udp -o $ETH0 --dport 53 -j ACCEPT
# iptables -A INPUT -p udp -i $ETH0 --sport 53 -j ACCEPT

## Se acepta DNS del proveedor
for ip in $DNS_SERVER
do
iptables -A OUTPUT -p udp -s $IP_UTEG_EQU --sport 1024:65535 -d $ip --dport 53 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A INPUT -p udp -s $ip --sport 53 -d $IP_UTEG_EQU --dport 1024:65535 -m state --state ESTABLISHED -j ACCEPT
iptables -A OUTPUT -p tcp -s $IP_UTEG_EQU --sport 1024:65535 -d $ip --dport 53 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A INPUT -p tcp -s $ip --sport 53 -d $IP_UTEG_EQU --dport 1024:65535 -m state --state ESTABLISHED -j ACCEPT
done

## Bloquear y prevenir ataques DDoS 
iptables -A INPUT -p tcp --dport 80 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
iptables -A INPUT -p tcp --dport 443 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT

## Cerramos otros puertos que estan abiertos
iptables -A INPUT -p udp --dport 5353 -j DROP

## Logging
iptables -N LOGGING
iptables -A INPUT -j LOGGING
iptables -A LOGGING -m limit --limit 2/min -j LOG --log-prefix "IPTables Packet Dropped: " --log-level 7
iptables -A LOGGING -j DROP

## Se guarda la configuracion de Iptables
iptables-save > /etc/sysconfig/iptables

sudo systemctl restart iptables
sudo systemctl restart ip6tables

echo "OK. Verifique que lo que se aplica con: iptables -L -n"

#proceso de bajar Firewalld e Instalar iptables
#sudo yum install iptables-services
#systemctl stop firewalld && sudo systemctl start iptables; sudo systemctl start ip6tables
#sudo systemctl disable firewalld
#sudo systemctl mask firewalld
#sudo systemctl enable iptables
#sudo systemctl enable ip6tables
