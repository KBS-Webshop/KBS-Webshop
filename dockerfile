FROM php:8.0-cli

COPY . /var/www/html

WORKDIR /var/www/html

EXPOSE 3100

CMD [ "php", "-S", "0.0.0.0:3100" ]
