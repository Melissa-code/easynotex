# Image officielle PHP avec Apache
FROM php:8.2-apache

# Installer extensions PHP nécessaires (notamment pour MySQL)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmariadb-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Active le module mod_rewrite pour Apache (nécessaire pour Laravel)
RUN a2enmod rewrite

# Installe Nano 
RUN apt-get update && apt-get install -y nano

# Modifie le fichier de configuration Apache pour pointer vers le dossier public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-enabled/000-default.conf

# Définit le répertoire de travail pour Apache
WORKDIR /var/www/html

# Copie le contenu du backend (fichiers Laravel) dans le conteneur
#COPY ./backend /var/www/html
COPY ./ /var/www/html

# Expose le port 80
EXPOSE 80
