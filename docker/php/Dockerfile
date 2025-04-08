FROM php:8.3-fpm

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y \
    git curl libpq-dev libzip-dev unzip zip \
    && docker-php-ext-install pdo_pgsql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar arquivos do projeto (opcional: use bind mount no docker-compose)
COPY . /var/www

# Permissões
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

EXPOSE 9000
