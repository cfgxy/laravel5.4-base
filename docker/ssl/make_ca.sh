#!/bin/sh

openssl genrsa -out ca.key 2048
openssl req -utf8 -x509 -key ca.key -nodes -new -out ca.crt -days 3650 -batch -subj "/C=CN/ST=Anhui/L=Hefei/O=Guxy-Dev/OU=Tech/CN=Guxy-Dev/emailAddress=xxx@xxx.com"

