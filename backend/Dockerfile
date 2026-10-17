FROM php:8.5-cli
## Installation paquets de base
RUN apt update
RUN apt install -y curl git wget libfreetype-dev libjpeg62-turbo-dev libpng-dev 
##PHP Extensions
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions intl

## Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Nettoyage du cache
RUN apt clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html