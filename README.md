# KLAXON - Application de Covoiturage Entreprise

## Description

KLAXON est une application web de covoiturage interne destinée aux employés d'une entreprise multi-sites. Elle permet de diffuser les trajets planifiés entre les différentes agences afin de favoriser le covoiturage et optimiser le taux d'occupation des véhicules.

L'application offre aux employés la possibilité de consulter les trajets disponibles, de proposer leurs propres trajets et de contacter les conducteurs. Un espace administrateur permet de gérer les agences, les utilisateurs et l'ensemble des trajets.

## Technologies utilisées

- **Backend** : PHP 7.4+
- **Base de données** : MySQL / MariaDB
- **Framework CSS** : Bootstrap 5.3
- **Préprocesseur CSS** : Sass
- **Architecture** : MVC (Modèle-Vue-Contrôleur)
- **Routeur** : izniburak/router 2.0
- **Tests** : PHPUnit 9.5
- **Analyse statique** : PHPStan 1.10
- **Gestion des dépendances** : Composer, npm

## Prérequis

Avant d'installer l'application, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- PHP 7.4 ou supérieur
- MySQL 5.7+ ou MariaDB 10.2+
- Composer
- Node.js et npm
- Serveur web (Apache, Nginx) ou PHP built-in server

## Installation

### 1. Cloner le dépôt

```bash
git clone <url-du-depot>
cd covoiturage-entreprise
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances npm

```bash
npm install
```

### 4. Configurer la base de données

Créez un fichier de configuration pour la base de données :

```bash
cp config/database.example.php config/database.php
```

Modifiez le fichier `config/database.php` avec vos paramètres MySQL :

```php
return [
    'host' => 'localhost',
    'dbname' => 'covoiturage_entreprise',
    'username' => 'votre_utilisateur',
    'password' => 'votre_mot_de_passe',
    'charset' => 'utf8mb4'
];
```

### 5. Créer la base de données

Exécutez les scripts SQL dans l'ordre suivant :

```bash
mysql -u votre_utilisateur -p < sql/create_tables.sql
mysql -u votre_utilisateur -p covoiturage_entreprise < sql/insert_agences.sql
mysql -u votre_utilisateur -p covoiturage_entreprise < sql/insert_users.sql
```

Alternativement, vous pouvez importer les fichiers via phpMyAdmin ou un autre client MySQL.

### 6. Compiler les assets Sass

```bash
npm run build
```

Pour le développement avec compilation automatique :

```bash
npm run sass:watch
```

### 7. Lancer le serveur de développement

Si vous utilisez XAMPP, placez le projet dans le dossier `htdocs` et accédez à :

```
http://localhost/covoiturage-entreprise/public/
```

Ou utilisez le serveur PHP intégré :

```bash
php -S localhost:8000 -t public
```

Puis accédez à `http://localhost:8000`

## Structure du projet

```
covoiturage-entreprise/
├── app/
│   ├── Controllers/         # Contrôleurs MVC
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── Controller.php
│   │   └── TrajetController.php
│   ├── Models/              # Modèles de données
│   │   ├── Agence.php
│   │   ├── Trajet.php
│   │   └── User.php
│   └── Database.php         # Classe de connexion PDO
├── assets/
│   ├── css/                 # CSS compilés
│   └── scss/                # Fichiers source Sass
│       └── app.scss
├── config/
│   └── database.php         # Configuration BDD
├── public/
│   └── index.php            # Point d'entrée de l'application
├── routes/
│   └── web.php              # Définition des routes
├── sql/
│   ├── create_tables.sql    # Script de création des tables
│   ├── insert_agences.sql   # Données des agences
│   └── insert_users.sql     # Données des utilisateurs
├── tests/
│   ├── Models/              # Tests unitaires des modèles
│   └── bootstrap.php        # Fichier de bootstrap des tests
├── views/
│   ├── admin/               # Vues de l'administration
│   ├── auth/                # Vues d'authentification
│   ├── trajets/             # Vues des trajets
│   └── layout.php           # Template principal
├── composer.json            # Dépendances PHP
├── package.json             # Dépendances npm
└── phpunit.xml              # Configuration PHPUnit
```

## Fonctionnalités

### Pour tous les visiteurs

- Consultation de la liste des trajets disponibles avec places restantes
- Trajets triés par date de départ croissante
- Affichage des informations basiques : ville de départ, date, ville d'arrivée, places disponibles

### Pour les employés connectés

- Accès aux informations complètes des trajets (conducteur, téléphone, email)
- Proposition de nouveaux trajets
- Modification de leurs propres trajets
- Suppression de leurs propres trajets

### Pour les administrateurs

- Tableau de bord avec statistiques
- Gestion complète des utilisateurs (consultation)
- Gestion des agences (création, modification, suppression)
- Gestion de tous les trajets (consultation, suppression)

## Identifiants de test

### Compte administrateur

- **Email** : admin@entreprise.fr
- **Mot de passe** : password123

### Compte utilisateur (exemple)

- **Email** : jean.dupont@entreprise.fr
- **Mot de passe** : password123

Note : Tous les utilisateurs de test utilisent le même mot de passe pour faciliter les tests.

## Tests

### Exécuter les tests unitaires

```bash
vendor/bin/phpunit
```

Les tests couvrent les opérations CRUD des modèles (User, Trajet, Agence).

### Analyse statique avec PHPStan

```bash
vendor/bin/phpstan analyse app --level=5
```

## Palette de couleurs

L'application utilise une palette de couleurs imposée, définie dans les variables Sass :

- Bleu principal : #0074c7
- Bleu foncé : #00497c
- Vert : #82b864
- Rouge : #cd2c2e
- Bleu très clair : #f1f8fc
- Gris foncé : #384050

Ces couleurs sont intégrées dans Bootstrap via les variables Sass pour faciliter la réutilisation dans d'autres projets.

## Sécurité

- Les mots de passe sont hashés avec bcrypt via `password_hash()`
- Protection des routes administrateur
- Vérification de propriété pour la modification et suppression de trajets
- Échappement des données avec `htmlspecialchars()` pour prévenir les attaques XSS
- Requêtes préparées (PDO) pour prévenir les injections SQL

## Contribution

Ce projet a été développé dans le cadre d'un projet académique. Le code est documenté et structuré pour faciliter sa reprise par d'autres développeurs.

## Base de données

La base de données contient :

- 12 agences (Paris, Lyon, Marseille, Toulouse, Nice, Nantes, Strasbourg, Montpellier, Bordeaux, Lille, Rennes, Reims)
- 21 utilisateurs (1 administrateur et 20 employés)
- Structure optimisée avec index et contraintes d'intégrité

## Licence

Ce projet est développé dans un cadre pédagogique.

## Support

Pour toute question ou problème, veuillez consulter la documentation ou contacter l'équipe de développement.
