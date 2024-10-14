# Laravel ve PHP kurulumu
FROM php:8.2-fpm

# Gerekli PHP eklentileri
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libjpeg-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql

# Graylog dizin izinleri
#RUN groupadd -r graylog && useradd -r -g graylog graylog \
#    && mkdir -p /etc/graylog \
#    && chown -R graylog:graylog /etc/graylog \
#    && chmod -R 770 /etc/graylog

# Composer kurulum
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Çalışma dizini
WORKDIR /var/www/html

# Projeyi kopyala
COPY . .

# Composer ile Laravel bağımlılıklarını kur
RUN composer install

# Laravel dosya izinleri
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Laravel cache ayarları
#RUN php artisan config:cache

# PHP-FPM başlat
CMD ["php-fpm"]
