# Usa imagem base PHP com Apache
FROM php:8.3-apache

# Define diretório de trabalho
WORKDIR /var/www/html

# Instala dependências do sistema e extensões PHP
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    libzip-dev \
    unzip \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libpq-dev \
    curl \
    gnupg \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd intl mbstring exif bcmath zip pcntl pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configura o Apache
RUN a2enmod rewrite headers ssl
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala Node.js e npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Copia package.json e package-lock.json primeiro
COPY package*.json ./
RUN npm install

# Copia o código da aplicação
COPY . .

# Instala dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Compila os assets
RUN npm run build

# Configura permissões
RUN mkdir -p storage/logs \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p bootstrap/cache \
    && touch storage/logs/laravel.log \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/public \
    && find /var/www/html/storage -type f -exec chmod 664 {} \; \
    && find /var/www/html/storage -type d -exec chmod 775 {} \;

# Script de inicialização
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Expõe a porta padrão do Apache
EXPOSE 80

# Usa o script de inicialização
CMD ["/usr/local/bin/start.sh"]
