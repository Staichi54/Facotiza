# Imagen base con PHP y Apache
FROM php:8.2-apache

# Instalar dependencias necesarias para los drivers de SQL Server
RUN apt-get update && apt-get install -y \
    gnupg2 \
    unixodbc \
    unixodbc-dev \
    libgssapi-krb5-2 \
    && rm -rf /var/lib/apt/lists/*

# Instalar drivers de SQL Server (sqlsrv y pdo_sqlsrv)
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Configuración de Apache (opcional: habilitar mod_rewrite)
RUN a2enmod rewrite

# Copiar el código de tu proyecto a la carpeta de Apache
COPY . /var/www/html/

# Ajustar permisos
RUN chmod -R 755 /var/www/html/

# Exponer puerto 80
EXPOSE 80

# Iniciar Apache en primer plano
CMD ["apache2-foreground"]
