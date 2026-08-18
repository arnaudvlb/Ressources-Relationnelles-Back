# Stage 1 : installation des dépendances PHP
FROM composer:2 AS dependencies

WORKDIR /app

COPY composer.json composer.lock symfony.lock ./

# Installe les dépendances PHP 
RUN composer install \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --no-dev \
    --no-scripts


# Stage 2 : image finale
FROM php:8.3-cli AS runner

WORKDIR /app


RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install \
    intl \
    pdo \
    pdo_mysql \
    zip \
    && curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash \
    && apt-get update \
    && apt-get install -y symfony-cli \
    && rm -rf /var/lib/apt/lists/*


COPY --from=dependencies /app/vendor ./vendor

COPY bin ./bin
COPY config ./config
COPY migrations ./migrations
COPY public ./public
COPY src ./src
COPY templates ./templates

COPY composer.json composer.lock symfony.lock ./


RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data /app


ENV HOME=/app
USER www-data


EXPOSE 8000


CMD ["symfony", "server:start", "--allow-http", "--no-tls", "--listen-ip=0.0.0.0"]