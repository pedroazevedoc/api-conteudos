FROM php:8.3-apache

# instalando dependências
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libicu-dev \
    libpq-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# instalando extensões do PHP
RUN docker-php-ext-install \
    pdo_mysql \
    zip \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    json

RUN pecl install pcov && docker-php-ext-enable pcov

WORKDIR /var/www/html

# configurando apache
COPY docker/app/apache.conf /etc/apache2/sites-available/site.conf
RUN a2dissite 000-default.conf
RUN a2enmod rewrite
RUN a2ensite site.conf

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# copiando o código para o container
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html/storage/
RUN chmod -R 755 /var/www/html/storage/

RUN git config --global --add safe.directory /var/www/html

RUN composer install

RUN php artisan key:generate

ENTRYPOINT apache2-foreground