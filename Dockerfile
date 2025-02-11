# Usa una imagen base oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Instala Composer manualmente dentro del contenedor
RUN apt-get update && apt-get install -y curl unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar el DocumentRoot para que apunte a `public/`
WORKDIR /var/www/html
RUN sed -i "s|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|" /etc/apache2/sites-available/000-default.conf

# Habilitar mod_rewrite para Slim
RUN a2enmod rewrite

# Copiar archivos del proyecto
COPY . /var/www/html/

# Instala dependencias con Composer
RUN composer install --no-dev --ignore-platform-reqs --optimize-autoloader || cat /var/www/html/composer.log

# Expone el puerto 80 para Apache
EXPOSE 80

# Inicia Apache
CMD ["apache2-foreground"]

# Reiniciar Apache
RUN service apache2 restart
