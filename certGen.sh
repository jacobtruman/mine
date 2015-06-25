#!/usr/bin/env bash

# Specify where we will install
# the certificate
SSL_DIR="/etc/apache2/ssl"

FILENAME="trucraft"

# Set the wildcarded domain
# we want to use
DOMAIN="*.trucraft.net"

# A blank passphrase
PASSPHRASE=""

# Set our CSR variables
SUBJ="
C=US
ST=Utah
O=
localityName=Spanish Fork
commonName=$DOMAIN
organizationalUnitName=TruCraft
emailAddress=jacob.truman@gmail.com
"

# Create our SSL directory
# in case it doesn't exist
sudo mkdir -p "$SSL_DIR"

# Generate our Private Key, CSR and Certificate
sudo openssl genrsa -out "$SSL_DIR/$FILENAME.key" 2048
sudo openssl req -new -subj "$(echo -n "$SUBJ" | tr "\n" "/")" -key "$SSL_DIR/$FILENAME.key" -out "$SSL_DIR/$FILENAME.csr" -passin pass:$PASSPHRASE
sudo openssl x509 -req -days 365 -in "$SSL_DIR/$FILENAME.csr" -signkey "$SSL_DIR/$FILENAME.key" -out "$SSL_DIR/$FILENAME.crt"
