FROM php:8.3-apache

# instalando dependências
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    build-essential \
    autoconf \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libpq-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# instalando extensões do PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install \
    pdo_mysql \
    zip \
    exif \
    pcntl \
    bcmath \
    gd \
    intl

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

# criando diretórios necessários e setando permissões
RUN mkdir -p /var/www/html/storage/logs && \
    mkdir -p /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage && \
    chown -R www-data:www-data /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage && \
    chmod -R 775 /var/www/html/bootstrap/cache

RUN git config --global --add safe.directory /var/www/html

RUN composer install --no-interaction --prefer-dist && \
    chown -R www-data:www-data /var/www/html

ENTRYPOINT apache2-foreground