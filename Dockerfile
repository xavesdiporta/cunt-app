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

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala Node.js e npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Configura o Apache
RUN a2enmod rewrite
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copia package.json e package-lock.json
COPY package*.json vite.config.js ./

# Instala dependências npm
RUN npm install

# Copia os ficheiros do Laravel
COPY . /var/www/html

# Instala dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Faz o build do Vite
RUN npm run build

# Define permissões para storage e cache
RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data /var/www/html

# Expõe a porta padrão do Apache
EXPOSE 80

# Usa o Apache para servir a aplicação
CMD ["apache2-foreground"]
