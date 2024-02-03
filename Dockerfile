FROM php:8.2-fpm

ARG LOCAL_UID
ARG LOCAL_GID

ENV LOCAL_UID=${LOCAL_UID}
ENV LOCAL_GID=${LOCAL_GID}

# Install ekstensi yang diperlukan oleh Laravel (including Nginx)
RUN apt-get update && apt-get install -y \
  git \
  curl \
  build-essential \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  libwebp-dev \ 
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libjpeg62-turbo \
  libzip-dev \
  zip \
  unzip \
  libpq-dev \
  nginx \
  nodejs \
  npm

# Install PDO MySQL extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd pdo_mysql bcmath opcache exif intl pdo_mysql zip mysqli

# Instal Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set direktori kerja ke direktori proyek Laravel Anda
WORKDIR /var/www/html

# Menyalin seluruh proyek Laravel ke dalam kontainer
COPY . /var/www/html

# permisson
RUN addgroup --gid ${LOCAL_GID} --system laravel
RUN adduser --group laravel --system --disabled-password --shell /bin/sh --uid ${LOCAL_UID}

RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf

RUN chown -R laravel:laravel /var/www/html
RUN chmod -R 755 /var/www/html/storage/

# Salin konfigurasi Nginx ke dalam kontainer
COPY nginx.conf /etc/nginx/sites-available/default

# Expose port 80 untuk Nginx dan port 9000 untuk PHP-FPM
EXPOSE 80
EXPOSE 9000

# CMD untuk menjalankan PHP-FPM dan Nginx
CMD service nginx start && php-fpm