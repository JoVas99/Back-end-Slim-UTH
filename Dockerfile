# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones necesarias para Slim y PDO
RUN docker-php-ext-install pdo pdo_mysql

# Instala Composer dentro del contenedor
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo en /var/www/html
WORKDIR /var/www/html

# Copia los archivos del proyecto al contenedor
COPY . .

# Instala dependencias de Composer
RUN composer install --no-dev --ignore-platform-reqs --optimize-autoloader

# Expone el puerto 80
EXPOSE 80

# Configura el comando de inicio del contenedor
CMD ["apache2-foreground"]
