FROM php:8.3-apache

# instalando dependências
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        build-essential autoconf git curl \
        libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libicu-dev libpq-dev unzip \
    && rm -rf /var/lib/apt/lists/*

# instalando extensões do PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd intl

RUN pecl install pcov && docker-php-ext-enable pcov

WORKDIR /var/www/html

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# copia apenas os arquivos de dependência do Composer
COPY composer.json composer.lock ./

# instala dependências
RUN composer install --no-interaction --prefer-dist --no-scripts --no-autoloader

# copiando o código para o container
COPY . .

# gera autoloader otimizado
RUN composer dump-autoload --optimize

# copia o entrypoint personalizado
COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# configurando apache
COPY docker/app/apache.conf /etc/apache2/sites-available/site.conf
RUN a2dissite 000-default.conf && \
    a2enmod rewrite && \
    a2ensite site.conf

# criando diretórios necessários e setando permissões
RUN mkdir -p /var/www/html/storage/logs /var/www/html/bootstrap/cache && \
    touch /var/www/html/storage/logs/laravel.log && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# configura o git
RUN git config --global --add safe.directory /var/www/html

# entrypoint personalizado
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]