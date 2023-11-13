#!/bin/bash
docker-php-ext-install mysqli

# Start de originele Docker-entrypoint van MariaDB
exec "$@"
