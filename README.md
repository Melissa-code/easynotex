# <img src="https://i.imgur.com/ySrca07.png" style="width: 30px;" /> EasyNotex 

Ce projet est application permettant aux utilisateurs de créer, organiser et gérer facilement leurs notes et tâches via une interface web intuitive.

EasyNotex est conçu avec une approche rigoureuse en prenant en compte les besoins des utilisateurs et une architecture bien définie: 

🔹 [Présentation du projet: contexte et besoin du client]()
🔹 [Maquettes UI]()
🔹 [UML Cas d'utilisation]()
🔹 [UML Diagramme de classes]()
🔹 [Kanban]()


⚠️ **Statut du projet: en cours de développement** 

--- 


## 1. Fonctionnalités principales

- **Gestion des Notes**: consulter, ajouter, modifier et supprimer des notes
- **Marquer des notes en favoris**: accès rapide aux notes importantes
- **Catégorisation**: organiser les notes par catégories ou favoris
- **Recherche et filtres**: trouver rapidement une note
- **Export PDF (optionnel)**: partager une note
- **Mode Sombre**: interface responsive avec TailwindCSS
- **Authentification et gestion des utilisateurs**:
  - Inscription et connexion obligatoires pour gérer ses notes
  - Admin: voir et gérer les notes des utilisateurs avec suppression des comptes
- **Tests**: PHPUnit (Laravel) et Vitest/Cypress (Vue.js)
- **Gestion avancée des erreurs**: logs

---


## 2. Technologies

- Conteneurisation: Docker et Docker Compose
- Back-end: Laravel API RESTful 
- Front-end: Vue.js + Vite
- UI: Tailwind CSS
- Base de données: MySQL
- Authentification: LAravel Scantum 
- Tests: PHPUnit (Laravel), Vitest et Cypress (Vue.js)
- Formatage du code: PHP-CS-Fixer
- IDE recommandé: VSCode
- CI/CD: (Optionnel: GitHub Actions)

---


## 3. Pré-requis 

- Docker et Docker Compose
- Git
- PHP 8+
- Composer
- Node.js 
- NPM

---


## 3. Installation et lancement du projet

#### 3.1. Cloner le projet:
```
git clone https://github.com/Melissa-code/easynotex.git
cd EasyNotex
```

#### 3.2. Installer les dépendances:

**Backend (Laravel):**
```
cd backend
composer install
(cp .env.example .env
php artisan key:generate)
```

**Frontend (Vue.js):**
```
cd ../frontend
npm install
```

#### 3.3. Lancer les services Docker: 

`docker-compose up -d` 

#### 3.4. Arrêter les services Docker:

`docker-compose down`

#### 3.5. Accéder aux services: 

- Backend Laravel:  `http://localhost:8000`
- Frontend Vue.js:`http://localhost:5173`

---


## 4. Authentification

Inscription et connexion via API Laravel Sanctum

---


## 5. Gestion des erreurs et logs

- Logs Laravel: Les erreurs backend sont enregistrées dans storage/logs/laravel.log
- Suivi des logs en direct: `tail -f storage/logs/laravel.log`

---


## 6. Règles de codage (Code Style et IDE recommandé)

- Utilisation de VSCode avec une barre verticale pour limiter le code à 120 caractères:
  - Activation dans VS Code:  Fichier/Préférences/Paramètres -> Rechercher "editor.rulers" puis modifier en JSON et ajouter `"editor.rulers": [120]`
- Formatage du code avec PHP-CS-Fixer (Laravel), ESLint/Prettier (Vue.js)

---

## 7. Aperçu de l'application web

<span style="display:flex; gap:2rem;">
  <img src="https://i.imgur.com/e6p5Gnp.png" alt="Aperçu de l'application EasyNotex" />
  <img src="https://i.imgur.com/TctqhfW.png" alt="Aperçu de l'application EasyNotex" />
</span>

---

## 8. Documentation du Projet

Voir doc.md (à venir)

--- 

## 9. Intérêt du projet 

-  **Gestion complète du CRUD & API:** notes, utilisateurs, authentification
-  **Sécurité et rôles utilisateurs:** Admin et utilisateurs avec permissions
-  **Bonne pratique en front et back:** Vue.js et Laravel API RESTful
-  **Déploiement facile avec Docker**

---

## 10. Author 

Melissa-code