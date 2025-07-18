# Dockerfile
FROM php:8.2-apache

# Copie tous tes fichiers dans le dossier du serveur
COPY . /var/www/html/
