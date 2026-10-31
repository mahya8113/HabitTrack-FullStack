FROM php:8.4-cli
RUN apt-get update && apt-get install -y git unzip libzip-dev libicu-dev libonig-dev default-mysql-client     && docker-php-ext-install pdo_mysql mbstring intl zip pcntl     && pecl install redis && docker-php-ext-enable redis     && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"     && php composer-setup.php --install-dir=/usr/local/bin --filename=composer     && rm composer-setup.php && apt-get clean && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www/html
