FROM php:8.3-apache

# Instalar las extensiones de PHP necesarias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar 'mod_rewrite' de Apache para URLs amigables
RUN a2enmod rewrite