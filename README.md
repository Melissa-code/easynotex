# EasyNotex 


--- 

## Installation

### 1. Configuration du docker-compose.yml

- Ouvrir l'application Docker Desktop (l'installer [Docker](https://docs.docker.com/desktop/setup/install/windows-install/))
- Créer le fichier à la racine du projet `docker-compose.yml`: 

```
services:
  # Backend: Laravel + Apache
  app:
    image: php:8.2-apache
    container_name: laravel_app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./backend:/var/www/html
    ports:
      - "8080:80" # Laravel (http://localhost:8080)
    depends_on:
      - db
    networks:
      - laravel

  # Base de données: MySQL
  db:
    image: mysql:8
    container_name: mysql_db
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: laravel_db
      MYSQL_USER: laravel
      MYSQL_PASSWORD: laravel_password
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - laravel

  # Frontend: Vue.js avec Node.js
  frontend:
    image: node:18
    container_name: vuejs_app
    working_dir: /app
    volumes:
      - ./frontend:/app
    ports:
      - "5173:5173" # Vue.js (http://localhost:5173)
    command: ["sh", "-c", "npm install && npm run dev"]
    depends_on:
      - app
    networks:
      - laravel

networks:
  laravel:

volumes:
  db_data:
```

---

### 2. Démarrer Docker et créer les containers

- Dans un terminal (Powershell par exemple), lancer la commande: 
`docker-compose up -d`

- Pour vérifier que les containers tournent bien: 
`docker ps`

---

### 3. Installer Laravel dans Docker 

- Entre dans le container Laravel: 
`docker exec -it laravel_app bash`

- Dans le terminal du container `root@665ad12e8a9c:/var/www#` : 
- Installer Composer : 
```
apt update && apt install -y curl unzip
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

```
- Vérifier l'installation avec `composer --version`

- Installer Laravel dans un sous-dossier: 
`composer create-project --prefer-dist laravel/laravel backend`

- Ajouter les identifiants pour la connexion à la base de données dans le .env: 
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=user
DB_PASSWORD=password
```

- Sortir du conteneur Laravel `exit`
- Relancer tous les conteneurs (-d) : 
`docker-compose up -d`


### 3. Installer Vuejs dans Docker 

- Supprimer `package-lock.json`: `cd frontend` puis `rm package-lock.json`
- Installer Vue: `npm create vite@latest . --template vue`
- Installer les dépendances: `npm install`
- Exécuter: `npm run dev`
- Tester l'URL: `http://localhost:5173/`
- Modifier cette ligne dans `package.json`: 
```
"scripts": {
  "dev": "vite --host",
  "build": "vite build",
  "preview": "vite preview"
},
```

- Revenir à la racine `cd ..`
- Lancer le backend + frontend avec Docker: `docker-compose up -d --build`

