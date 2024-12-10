# Bước 1: Sử dụng PHP image có sẵn với Apache
FROM php:8.1-apache

# Bước 2: Cài đặt các dependencies cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Bước 3: Cài đặt Composer (công cụ quản lý PHP dependencies)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Bước 4: Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Bước 5: Sao chép mã nguồn vào container
COPY . .

# Bước 6: Cài đặt các phụ thuộc Laravel thông qua Composer
RUN composer install --no-interaction --prefer-dist

# Bước 7: Thiết lập quyền sở hữu cho các thư mục cần thiết
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Bước 8: Kích hoạt mod_rewrite của Apache (quan trọng cho Laravel)
RUN a2enmod rewrite

# Bước 9: Cấu hình Apache để cho phép .htaccess và mod_rewrite
# Sao chép cấu hình Apache của bạn vào container (vhost.conf)
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Bước 10: Expose cổng 80
EXPOSE 80

# Bước 11: Khởi động Apache khi container chạy
CMD ["apache2-foreground"]
