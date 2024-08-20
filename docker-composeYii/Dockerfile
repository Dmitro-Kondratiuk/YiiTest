# Используйте официальный PHP-образ в качестве базового
FROM php:8.2-fpm

# Установите зависимости и расширения
RUN apt-get update && \
    apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmariadb-dev \
    libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Установите Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копируйте исходный код в контейнер
COPY . /var/www/html

# Установите рабочий каталог
WORKDIR /var/www/html

# Установите права доступа
RUN chown -R www-data:www-data /var/www/html

# Укажите команду для запуска PHP-FPM
CMD ["php-fpm"]
