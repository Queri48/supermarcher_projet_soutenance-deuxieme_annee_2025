# Dockerfile
FROM php:8.2-apache

# Installer l'extension mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copier tous tes fichiers dans le serveur
COPY . /var/www/html/
