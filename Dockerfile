# Imagine de bază cu Apache + PHP
FROM php:8.1-apache

# Copiază fișierele aplicației în container
COPY app/ /var/www/html/

# Activează extensia mysqli pentru conectarea la baza de date
RUN docker-php-ext-install mysqli
