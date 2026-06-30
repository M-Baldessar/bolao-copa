#!/bin/bash

set -e

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"

cd "$PROJECT_DIR"

echo "--- Pulling do GitHub ---"
git checkout -- package-lock.json composer.lock 2>/dev/null || true
git pull origin main

echo "--- Instalando dependências PHP ---"
composer install --no-dev --optimize-autoloader

echo "--- Compilando assets ---"
npm install
./node_modules/.bin/vite build

echo "--- Rodando migrations ---"
php artisan migrate --force

echo "--- Limpando caches ---"
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

echo "--- Deploy concluído com sucesso ---"
