# Usando uma imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instalando extensões e dependências necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mbstring pdo pdo_mysql xml bcmath opcache

# Habilitando módulos do Apache
RUN a2enmod rewrite

# Definindo o diretório de trabalho
WORKDIR /var/www/html

# Copiando o arquivo composer.json e composer.lock para o diretório de trabalho
COPY composer.json composer.lock ./

# Instalando as dependências do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-scripts --no-autoloader

# Copiando o código da aplicação
COPY . .

# Copiando a configuração do Apache
COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Gerando o autoload do Composer
RUN composer dump-autoload

# Definindo as permissões corretas
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Removendo o arquivo index.html padrão do Apache
RUN rm -f /var/www/html/index.html

# Expondo a porta 8000
EXPOSE 8000

# Iniciando o Apache
CMD ["apache2-foreground"]
