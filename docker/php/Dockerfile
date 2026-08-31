ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-fpm

ARG UID=1000
ARG GID=1000

# ============================================================
# System dependencies
# ============================================================

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install \
        bcmath \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_mysql \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*


# ============================================================
# Composer
# ============================================================

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# ============================================================
# Configure www-data UID/GID
# ============================================================

RUN groupmod -o -g ${GID} www-data \
    && usermod -o -u ${UID} -g ${GID} www-data


# ============================================================
# PHP CLI / Composer / PsySH directories
# ============================================================

RUN mkdir -p \
        /var/www/.config/psysh \
        /var/www/.cache \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data \
        /var/www/.config \
        /var/www/.cache \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chmod -R ug+rwx \
        /var/www/.config \
        /var/www/.cache \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache


# ============================================================
# Environment
# ============================================================

ENV HOME=/var/www
ENV XDG_CONFIG_HOME=/var/www/.config
ENV XDG_CACHE_HOME=/var/www/.cache


# ============================================================
# Working directory
# ============================================================

WORKDIR /var/www/html


# ============================================================
# Run as host user
# ============================================================

USER www-data


# ============================================================
# PHP-FPM
# ============================================================

CMD ["php-fpm"]
