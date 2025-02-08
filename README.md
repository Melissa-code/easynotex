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
`docker-compose up -d`; `docker-compose down`

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


---

## Création de la base de données

1. Créer les migrations (définition des tables dans la base de données)

- Dans le conteneur Laravel `docker exec -it laravel_app bash`
- la table users existe déjà par défault, crée les 2 autres: 
- `php artisan make:migration create_categories_table`
- `php artisan make:migration modify_users_table --table=users`
- `php artisan make:migration create_notes_table`


2. Exécuter les migrations 

- Applique les migrations `php artisan migrate`
- Pour annuler `php artisan migrate:rollback`
- sinon, entrer dans le conteneur mysql: `docker exec -it mysql_db bash`
- se connecter à la base de données `mysql -u username -p` 
- se déplacer dans la base de données: `USE laravel_db`

- Vérifier la création des tables avec `php artisan migrate:status` 


3. Créer les modèles Eloquent pour chaque table 
`php artisan make:model Category`


4. Ajouter les relations dans les modèles

- dans les models, ajouter les relations one to many:
`hasMany`, `belongsTo`


5. Insérer des données de tests

- Créer des seeders: `php artisan make:seeder CategoriesTableSeeder`
- Remplir les seeders avec des données 
- Utiliser Faker pour générer des données aléatoires
- Lier les seeders à DatabaseSeeder pour qu'ils soient exécutés avec `php artisan db:seed`
- Vérifier si les données sont bien insérées dans la base en lignes de commande `SELECT * FROM categories;`

- Pour créer un Administrateur changer le rôle d'un user 
- `php artisan tinker`
- Modifier l'utilisateur ID n°1: 
```
$user = App\Models\User::find(1);
$user->role = 'admin'; 
$user->save(); 
```
- Vérifier le changement `App\Models\User::find(1)->role;`
```
App\Models\User::find(1);
= App\Models\User {#5195
    id: 1,
    first_name: "Ava",
    last_name: "Keebler",
    email: "dare.raymundo@koch.com",
    email_verified_at: null,
    #password: "$2y$12$g1kuEKgx/m/c8cKE.xumHOxn02mNF.l208QMD38xtMvduwF4r5zb.",
    role: "admin",
    #remember_token: null,
    created_at: "2025-02-08 14:50:24",
    updated_at: "2025-02-08 15:02:01",
  }
```

---

5. Créer les contrôleurs API (php artisan make:controller).
6. Créer les routes API (routes/api.php).
7. Implémenter les fonctionnalités CRUD dans les contrôleurs.
8. Tester avec Postman ou un client API.