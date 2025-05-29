#!/bin/bash

# Garante as permissões corretas em tempo de execução
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
find /var/www/html/storage -type f -exec chmod 664 {} \;
find /var/www/html/storage -type d -exec chmod 775 {} \;

# Limpa e gera o cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Inicia o Apache
apache2-foreground
