#!/bin/bash

# ajusta permissões dos diretórios do laravel
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# inicia o apache
apache2-foreground