# Touche pas au Klaxon 🚗

Application de covoiturage d'entreprise développée en PHP avec architecture MVC.

## 📋 Description

Application permettant aux employés de proposer et consulter des trajets de covoiturage entre les différentes agences de l'entreprise.

## 🚀 Installation

### Prérequis

- PHP >= 8.0
- MySQL/MariaDB
- Composer
- Serveur web (Apache/Nginx)

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone https://github.com/Masty1988/covoiturage-entreprise.git
cd covoiturage-entreprise
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer la base de données**

Éditer `config/database.php` avec vos paramètres :
```php
'host' => 'localhost',
'database' => 'covoiturage',
'username' => 'votre_user',
'password' => 'votre_password',
```

4. **Créer la base de données**
```bash
mysql -u root -p
```
```sql
CREATE DATABASE covoiturage CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE covoiturage;
source sql/create_tables.sql;
source sql/insert_agences.sql;
source sql/insert_users.sql;
source sql/insert_trajets_demo.sql;
```

5. **Configurer le serveur web**

**Apache** : Pointer le DocumentRoot vers le dossier `public/`

**PHP Built-in Server** (développement) :
```bash
php -S localhost:8000 -t public/
```

## 👥 Identifiants de connexion

**Administrateur :**
- Email: `admin@email.fr`
- Mot de passe: `admin123`

**Utilisateur test :**
- Email: `alexandre.martin@email.fr`
- Mot de passe: `password123`

## 🏗️ Architecture
```
├── config/           # Configuration (BDD, routes)
├── public/           # Point d'entrée (index.php)
├── src/
│   ├── Controllers/  # Contrôleurs
│   ├── Models/       # Modèles et repositories
│   ├── Views/        # Vues (templates)
│   ├── Core/         # Classes du framework
│   ├── Middlewares/  # Middlewares (Auth, Admin)
│   └── Utils/        # Utilitaires (Session, Flash, etc.)
├── sql/              # Scripts SQL
└── tests/            # Tests unitaires
```

## 🧪 Tests
```bash
composer test          # Lance tous les tests
composer test:unit     # Tests unitaires uniquement
```

## 📊 Qualité du code
```bash
composer analyse       # Analyse avec PHPStan
```

## 📝 Fonctionnalités

### Visiteur (non connecté)
- ✅ Consulter les trajets disponibles

### Utilisateur connecté
- ✅ Voir les détails d'un trajet
- ✅ Créer un trajet
- ✅ Modifier ses propres trajets
- ✅ Supprimer ses propres trajets

### Administrateur
- ✅ Gérer les utilisateurs
- ✅ Gérer les agences
- ✅ Gérer tous les trajets
- ✅ Tableau de bord complet

## 📜 Licence

MIT

## 👨‍💻 Auteur

**Nicolas - Masty1988**

- GitHub: [@Masty1988](https://github.com/Masty1988)