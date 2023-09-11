FROM php

WORKDIR /var/www
COPY ./src /var/www

RUN docker-php-ext-install mysqli

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/var/www"]