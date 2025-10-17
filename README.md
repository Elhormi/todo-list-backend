# API Backend pour Application To-Do List

Ce dépôt contient le code source de l'API backend pour une application "To-Do List" Full Stack. Développée avec **Laravel 11**, cette API fournit un système d'authentification sécurisé basé sur JWT, un CRUD complet pour la gestion des tâches et des notifications en temps réel avec Pusher.

## ✨ Fonctionnalités Principales

-   🔐 **Authentification Sécurisée** : Inscription et connexion basées sur les JSON Web Tokens (JWT).
-   📋 **Gestion des Tâches (CRUD)** : Opérations complètes de création, lecture, mise à jour et suppression des tâches.
-   👤 **Isolation des Données** : Chaque utilisateur ne peut voir et gérer que ses propres tâches.
-   🚀 **Notifications en Temps Réel** : Envoi d'un événement via Pusher à chaque création de nouvelle tâche.
-   🏗️ **Architecture Robuste** : Application des principes SOLID et des design patterns Service/Repository.

---

## 🛠️ Guide d'Installation et de Configuration

Suivez ces étapes pour installer et lancer le projet sur votre machine locale.

### Prérequis

-   PHP >= 8.2
-   Composer
-   Un serveur de base de données (MySQL / MariaDB ou PostgreSQL)
-   Un compte [Pusher](https://pusher.com/) (le plan gratuit "sandbox" est suffisant)

### 1. Cloner le Dépôt

Naviguez jusqu'à votre répertoire de travail et clonez ce projet :

```bash
git clone https://github.com/VOTRE-NOM-UTILISATEUR/todo-list-backend.git
cd todo-list-backend
```

### 2. Installer les Dépendances PHP

Utilisez Composer pour installer tous les packages nécessaires au projet.

```bash
composer install
```

### 3. Configuration de l'Environnement

Le fichier `.env` contient toutes les variables de configuration de votre application.

-   **Étape 3.1 : Créer le fichier .env**
    Copiez le fichier d'exemple pour créer votre propre fichier de configuration.
    ```bash
    cp .env.example .env
    ```

-   **Étape 3.2 : Générer les Clés de Sécurité**
    Générez la clé de l'application et la clé secrète pour JWT.
    ```bash
    php artisan key:generate
    php artisan jwt:secret
    ```

-   **Étape 3.3 : Configurer la Base de Données**
    Ouvrez le fichier `.env` et modifiez les lignes suivantes avec les informations de votre base de données locale (exemple pour XAMPP/WAMP) :
    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=todo_list_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```
    *N'oubliez pas de créer manuellement la base de données `todo_list_db` dans votre gestionnaire de BDD (phpMyAdmin, TablePlus, etc.).*

-   **Étape 3.4 : Configurer Pusher**
    Dans le même fichier `.env`, renseignez les clés de votre application Pusher :
    ```dotenv
    BROADCAST_DRIVER=pusher

    PUSHER_APP_ID=VOTRE_APP_ID
    PUSHER_APP_KEY=VOTRE_APP_KEY
    PUSHER_APP_SECRET=VOTRE_APP_SECRET
    PUSHER_APP_CLUSTER=VOTRE_APP_CLUSTER
    ```

### 4. Lancer les Migrations

Cette commande va créer toutes les tables nécessaires (`users`, `tasks`, etc.) dans votre base de données.

```bash
php artisan migrate
```

### 5. Démarrer le Serveur

Félicitations ! Votre API est prête à être lancée.

```bash
php artisan serve
```

Le serveur de développement démarrera, et votre API sera accessible à l'adresse **`http://127.0.0.1:8000`**.

---

## 🧪 Utilisation et Test de l'API

Il est recommandé d'utiliser un client API comme [Postman](https://www.postman.com/) pour tester les endpoints.

### Authentification

| Méthode | Endpoint                     | Description                                      | Body (form-data)                                       |
| :------ | :--------------------------- | :----------------------------------------------- | :----------------------------------------------------- |
| `POST`  | `/api/auth/register`         | Crée un nouvel utilisateur.                      | `full_name`, `email`, `password`, `phone_number`*, `address`* |
| `POST`  | `/api/auth/login`            | Connecte un utilisateur et retourne un token JWT. | `email`, `password`                                    |

*Les champs marqués d'une astérisque sont optionnels.*

### Tâches

**Note :** Toutes les routes suivantes sont protégées et nécessitent un en-tête d'autorisation : `Authorization: Bearer <votre_token_jwt>`.

| Méthode  | Endpoint         | Description                   | Body (form-data / x-www-form-urlencoded)               |
| :------- | :--------------- | :---------------------------- | :----------------------------------------------------- |
| `GET`    | `/api/tasks`     | Liste les tâches de l'utilisateur. | (aucun)                                                |
| `POST`   | `/api/tasks`     | Crée une nouvelle tâche.      | `title`, `description`*                                |
| `GET`    | `/api/tasks/{id}`  | Affiche une tâche spécifique. | (aucun)                                                |
| `PUT`    | `/api/tasks/{id}`  | Met à jour une tâche.         | `title`*, `description`*, `completed`* (0 ou 1)        |
| `DELETE` | `/api/tasks/{id}`  | Supprime une tâche.           | (aucun)                                                |````
