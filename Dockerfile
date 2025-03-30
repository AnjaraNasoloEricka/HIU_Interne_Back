# Utiliser l'image PHP 8 basée sur Alpine
FROM php:8-alpine

# Installer PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Définir le répertoire de travail
WORKDIR /var/www

# Copier tout le contenu du projet dans le conteneur
COPY . /var/www

# Exposer le port 8080 pour la communication
EXPOSE 8080

# Lancer le serveur PHP intégré
CMD ["php", "-S", "0.0.0.0:8080", "index.php"]
