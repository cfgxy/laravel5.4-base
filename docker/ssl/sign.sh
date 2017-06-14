#!/bin/sh
openssl x509 -req -sha256 -in server.csr -CA ca.crt -CAkey ca.key -CAcreateserial -days 3650 -out server.crt -extensions v3_req -extfile server.cnf
