# Image de base
FROM php:8.2-apache as builder

# Installer les extensions PHP requises
RUN docker-php-ext-install pdo_mysql

RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
  && docker-php-ext-install pdo_mysql

RUN php -m | grep pdo_mysql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copier l'application Laravel dans le conteneur
COPY ./ /var/www/html

# Installer les dépendances avec Composer
RUN composer install --no-dev --no-scripts --no-progress --prefer-dist

# --- 

FROM php:8-apache

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Changer le document root d'Apache vers /public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Copier les fichiers de l'application et les dépendances installées
COPY --from=builder /var/www/html /var/www/html

# Donner à Apache la propriété du dossier de l'application
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80 pour Apache
EXPOSE 80
