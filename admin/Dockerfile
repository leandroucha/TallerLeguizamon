FROM php:8.2-apache

# Instalar la extensión PDO MySQL
RUN docker-php-ext-install pdo_mysql

# (Opcional pero recomendado) habilitar mod_rewrite
RUN a2enmod rewrite

# (Opcional) configurar timezone dentro del contenedor
# ENV TZ=America/Argentina/Cordoba
