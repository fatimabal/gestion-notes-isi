#!/bin/bash
set -e

# Générer APP_KEY si pas défini
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Créer .env depuis les variables d'environnement Render
cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME:-GestionNotesISI}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file

MAIL_MAILER=${MAIL_MAILER:-smtp}
MAIL_HOST=${MAIL_HOST:-}
MAIL_PORT=${MAIL_PORT:-587}
MAIL_USERNAME=${MAIL_USERNAME:-}
MAIL_PASSWORD=${MAIL_PASSWORD:-}
MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-tls}
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-noreply@isi.sn}
MAIL_FROM_NAME="ISI Gestion Notes"

SANCTUM_STATEFUL_DOMAINS="${SANCTUM_STATEFUL_DOMAINS:-localhost}"
SESSION_DOMAIN="${SESSION_DOMAIN:-}"
EOF

# Migrations
php artisan migrate --force

# Cache config
php artisan config:cache
php artisan route:cache

# Lancer Apache
exec "$@"
