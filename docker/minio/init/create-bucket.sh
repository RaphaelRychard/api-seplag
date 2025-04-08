#!/bin/sh

# Aguarda o MinIO subir
sleep 5

# Cria o bucket
mc alias set local http://localhost:9000 myadmin mysecurepassword
mc mb --ignore-existing local/seplag
