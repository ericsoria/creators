FROM php:8.4.18-fpm

ARG user
ARG uid

RUN apt-get update && apt-get install -y \
    git \
    curl \
    pkg-config \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmemcached-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    openssh-server \
    zip \
    unzip \
    sqlite3 \
    nano \
    cron \
    redis

# Install Node.js (LTS) + npm
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && npm install -g npm

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j"$(nproc)" zip mbstring exif pcntl bcmath gd intl
RUN docker-php-ext-install pdo_mysql pdo_pgsql
RUN docker-php-ext-install opcache

# Install PHP Redis extension for Laravel phpredis client
RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && chown -R $user:$user /home/$user

WORKDIR /var/www
USER $user