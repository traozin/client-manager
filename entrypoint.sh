#!/bin/bash
set -e

if [ ! -f ".env" ]; then
  echo "📄 Arquivo .env não encontrado. Copiando .env.example..."
  cp .env.example .env
fi

echo "⏳ Aguardando o banco de dados ficar disponível..."
until mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e 'SELECT 1' >/dev/null 2>&1; do
  sleep 2
done

if [ ! -d "vendor" ]; then
  echo "📦 Instalando dependências do Composer..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

echo "🔐 Ajustando permissões..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

if ! grep -q "APP_KEY=base64" .env; then
  echo "🔑 Gerando chave da aplicação..."
  php artisan key:generate
fi

echo "🧩 Executando migrations..."
php artisan migrate --force

echo "🚀 Iniciando servidor Laravel na porta 8000..."
exec php artisan serve --host=0.0.0.0 --port=8000