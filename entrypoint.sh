#!/bin/bash
docker-php-ext-install mysqli
apachectl restart

# Start de originele Docker-entrypoint van MariaDB
exec "$@"
