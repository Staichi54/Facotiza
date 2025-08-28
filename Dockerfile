# Usar PHP con Apache como imagen base
FROM php:8.2-apache

# Copiar tu proyecto al directorio de Apache
COPY . /var/www/html/

# Dar permisos a los archivos (opcional, pero recomendado)
RUN chmod -R 755 /var/www/html/

# Exponer el puerto por el que corre Apache
EXPOSE 80
