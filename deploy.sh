#!/bin/bash

set -e

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"

cd "$PROJECT_DIR"

echo "--- Pulling do GitHub ---"
git pull origin main

echo "--- Instalando dependências PHP ---"
composer install --no-dev --optimize-autoloader

echo "--- Compilando assets ---"
npm install
npm run build

echo "--- Rodando migrations ---"
php artisan migrate --force

echo "--- Limpando caches ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- Deploy concluído com sucesso ---"
