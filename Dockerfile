# ==========================================
# Etapa 1: Composer (Dependencias PHP)
# ==========================================
FROM composer:2.7 AS vendor

WORKDIR /app

# Copiar archivos necesarios para composer install
COPY composer.json composer.lock ./

# Copiar la carpeta packages para que los repositorios locales funcionen
COPY packages/ packages/

# Instalar dependencias (ignorando scripts por ahora)
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# ==========================================
# Etapa 2: Node (Assets Frontend)
# ==========================================
FROM node:20-alpine AS frontend

WORKDIR /app

# Copiar package.json y package-lock.json
COPY package*.json postcss.config.js tailwind.config.js vite.config.js ./

# Instalar dependencias
RUN npm ci

# Copiar el resto del código necesario para compilar assets
COPY resources/ resources/
COPY public/ public/

# Compilar assets para producción
RUN npm run build

# ==========================================
# Etapa 3: Producción (FrankenPHP o PHP-FPM)
# Usaremos la imagen oficial de PHP con Apache por simplicidad y estabilidad
# ==========================================
FROM php:8.2-apache

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Instalar dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd pcntl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configurar el DocumentRoot de Apache a la carpeta public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Copiar la aplicación
COPY . .

# Copiar dependencias de PHP desde la etapa vendor
COPY --from=vendor /app/vendor/ vendor/

# Copiar assets compilados desde la etapa frontend
COPY --from=frontend /app/public/build/ public/build/

# Establecer permisos
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Puerto expuesto por Apache
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
