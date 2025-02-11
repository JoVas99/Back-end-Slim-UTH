# Usa una imagen base oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Instala Composer manualmente dentro del contenedor
RUN apt-get update && apt-get install -y curl unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establece el directorio de trabajo en /var/www/html
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Instala dependencias con Composer
RUN composer install --no-dev --ignore-platform-reqs --optimize-autoloader || cat /var/www/html/composer.log

# Expone el puerto 80 para Apache
EXPOSE 80

# Inicia Apache
CMD ["apache2-foreground"]
