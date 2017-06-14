#!/bin/bash

openssl genrsa -out server.key 2048
openssl req -utf8 -new -reqexts v3_req -key server.key -out server.csr -config server.cnf -batch
