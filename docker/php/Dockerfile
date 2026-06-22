FROM php:8.3-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libgd-dev \
    libicu-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP necessárias
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache \
    intl

# Redis via PECL
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cria usuário não-root para rodar o app
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

WORKDIR /var/www

ARG UID=1364806413
ARG GID=1364800513
RUN groupadd -g ${GID} appuser 2>/dev/null || true \
    && useradd -u ${UID} -g ${GID} -ms /bin/bash appuser 2>/dev/null || true

USER appuser

EXPOSE 9000
CMD ["php-fpm"]
