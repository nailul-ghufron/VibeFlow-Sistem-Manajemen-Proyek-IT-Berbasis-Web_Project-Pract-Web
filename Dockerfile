FROM php:8.2-apache

# Mengaktifkan mod_rewrite untuk router .htaccess VibeFlow
RUN a2enmod rewrite

# Menginstal ekstensi pdo_mysql untuk komunikasi ke database
RUN docker-php-ext-install pdo pdo_mysql

# Menyesuaikan hak akses untuk folder uploads nanti
RUN chown -R www-data:www-data /var/www/html
