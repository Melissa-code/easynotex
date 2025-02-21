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

## 6. Créer les routes API (routes/api.php).

- Vérifier le chargement des routes dans Providers/AppServiceProvider méthode boot(); 
- Après avoir créer le contrôleur et sa méthode
- Vérifier l'existance de la route: php artisan route:list

## 7. Créer les contrôleurs API (php artisan make:controller).

`php artisan make:controller NoteController`


- Implémenter les fonctionnalités CRUD dans les contrôleurs.
- Tester avec Postman ou un client API.


## Code style

### Laravel Pint est un correcteur de style de code PHP. 

- Il est construit sur PHP-CS-Fixer et permet de garantir facilement que le style de votre code reste propre et cohérent
- Pint est automatiquement installé avec toutes les nouvelles applications Laravel
- Dans le container laravel_app lance la commande: `./vendor/bin/pint`


### Pour utiliser PHP-CS-Fixer: 

- Installer Composer dans le container laravel_app: `curl -sS https://getcomposer.org/installer | php`
- Vérifier `php composer.phar --version`
- Pour utiliser Composer globalement dans ton conteneur, il faut le déplacer:
`mkdir -p /usr/local/bin` puis `mv composer.phar /usr/local/bin/composer`
- Vérifier `composer --version`

- Installer php-cs-fixer `composer require --dev friendsofphp/php-cs-fixer`
- Ajoute ce fichier `touch .php-cs-fixer.php`
- Installer `composer require --dev gordinskiy/line-length-checker`
- cf [gordinskiy](https://packagist.org/packages/gordinskiy/line-length-checke)`
- Ajouter dans le fichier `.php-cs-fixer.php`: 
```
return (new PhpCsFixer\Config())
    // ...
    ->setRules([
        'Gordinskiy/line_length_limit' => ['max_length' => 115],
    ])
;
```
- ajout de la barre verticale de 120 caractères, recommandé pour une meilleure lisibilité du code ( Fichier → Préférences → Settings → serach "editor.rulers" Clique sur "Modifier en JSON" et ajoute "editor.rulers": [120] )

- Pour vérifier : `php vendor/bin/php-cs-fixer fix --dry-run`
- Pour voir les différences `php vendor/bin/php-cs-fixer fix app/Repositories/NoteRepository.php --dry-run --diff`
- Pour corriger: `php vendor/bin/php-cs-fixer fix app/Repositories/NoteRepository.php`

### Gestion des erreurs 

- Voir les logs dans le fichier `storage/logs/laravel.log` ou bien taper `tail -f storage/logs/laravel.log`


### Tests unitaires 
- `php artisan make:test UserTest --unit`
- `php artisan test --filter NoteControllerTest`


### Tailwind CSS
- lien CDN

- pour les date: `npm install date-fns`

