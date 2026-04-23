FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip gd bcmath intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/framework/testing \
    storage/logs storage/app/public bootstrap/cache

RUN composer install --no-interaction --optimize-autoloader

RUN chmod +x docker/start.sh

# Render injects $PORT at runtime; locally docker-compose maps 8010:8000.
EXPOSE 8000

CMD ["docker/start.sh"]
