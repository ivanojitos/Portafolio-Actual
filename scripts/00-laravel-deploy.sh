#!/usr/bin/env bash

set -e

cd /var/www/html

echo "Instalando dependencias de producción..."
composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Cargando datos iniciales..."
php artisan db:seed --force

echo "Creando enlace de almacenamiento..."
php artisan storage:link || true

echo "Optimizando Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Laravel preparado correctamente."
