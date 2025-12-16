FROM php:8.4-apache

# Instalar dependencias necesarias incluyendo Node.js
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mysqli

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Cambiar DocumentRoot a /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# ✅ Laravel debe trabajar desde /var/www/html
WORKDIR /var/www/html

# Permisos
RUN chown -R www-data:www-data /var/www/html
