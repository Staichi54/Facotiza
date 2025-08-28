FROM php:8.2-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    unixodbc-dev \
    gnupg2 \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de Microsoft SQL Server
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Copiar tu proyecto al contenedor
COPY . /var/www/html/

# Dar permisos
RUN chmod -R 755 /var/www/html/

# Exponer Apache
EXPOSE 80

