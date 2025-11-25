# Guide d'Installation - Application KLAXON

Ce document decrit la procedure complete pour installer et configurer l'application KLAXON sur votre environnement de developpement.

## Prerequis

Avant de commencer l'installation, assurez-vous que votre systeme dispose des elements suivants :

### Logiciels requis

- PHP 7.4 ou superieur
- MySQL 5.7+ ou MariaDB 10.2+
- Composer (gestionnaire de dependances PHP)
- Node.js et npm (pour la compilation des assets)
- Serveur web Apache ou Nginx (ou serveur PHP integre pour le developpement)

### Verification des versions

Vous pouvez verifier les versions installees avec les commandes suivantes :

```bash
php -v
mysql --version
composer --version
node -v
npm -v
```

## Etapes d'installation

### 1. Recuperation du code source

Clonez le depot Git dans votre repertoire de travail :

```bash
git clone <url-du-depot-github>
cd covoiturage-entreprise
```

### 2. Installation des dependances PHP

Installez les dependances PHP avec Composer :

```bash
composer install
```

Cette commande installera :
- izniburak/router 2.0
- vlucas/phpdotenv 5.5
- PHPUnit 9.5 (dev)
- PHPStan 1.10 (dev)

### 3. Installation des dependances npm

Installez les dependances npm necessaires pour la compilation des assets :

```bash
npm install
```

Cette commande installera :
- Bootstrap 5.3.8
- Sass 1.94.2

### 4. Configuration de la base de donnees

#### 4.1. Creer le fichier de configuration

Copiez le fichier de configuration exemple :

```bash
cp config/database.example.php config/database.php
```

#### 4.2. Modifier les parametres de connexion

Editez le fichier `config/database.php` et ajustez les parametres selon votre environnement :

```php
<?php
return [
    'host' => 'localhost',
    'dbname' => 'covoiturage_entreprise',
    'username' => 'root',           // Remplacez par votre utilisateur MySQL
    'password' => '',               // Remplacez par votre mot de passe MySQL
    'charset' => 'utf8mb4'
];
```

#### 4.3. Creer la base de donnees

Connectez-vous a MySQL et executez les scripts SQL dans l'ordre :

**Option 1 : Via la ligne de commande**

```bash
# Creer la base et les tables
mysql -u root -p < sql/create_tables.sql

# Inserer les donnees des agences
mysql -u root -p covoiturage_entreprise < sql/insert_agences.sql

# Inserer les donnees des utilisateurs
mysql -u root -p covoiturage_entreprise < sql/insert_users.sql
```

**Option 2 : Via phpMyAdmin**

1. Ouvrez phpMyAdmin dans votre navigateur
2. Importez dans l'ordre :
   - `sql/create_tables.sql`
   - `sql/insert_agences.sql`
   - `sql/insert_users.sql`

**Option 3 : Via un client MySQL (MySQL Workbench, HeidiSQL, etc.)**

1. Connectez-vous a votre serveur MySQL
2. Executez le contenu de chaque fichier SQL dans l'ordre indique

### 5. Compilation des assets Sass

Compilez les fichiers Sass en CSS :

```bash
npm run build
```

Pour le developpement avec recompilation automatique lors des modifications :

```bash
npm run sass:watch
```

Cette commande surveille les modifications dans `assets/scss/` et recompile automatiquement le CSS.

### 6. Configuration du serveur web

#### Option A : Utilisation avec XAMPP (Windows)

1. Placez le projet dans le dossier `C:\xampp\htdocs\`
2. Demarrez Apache et MySQL depuis le panneau de controle XAMPP
3. Acces a l'application : `http://localhost/covoiturage-entreprise/public/`

#### Option B : Utilisation avec MAMP (macOS)

1. Placez le projet dans le dossier `/Applications/MAMP/htdocs/`
2. Demarrez les serveurs Apache et MySQL
3. Acces a l'application : `http://localhost:8888/covoiturage-entreprise/public/`

#### Option C : Serveur PHP integre (tous systemes)

Pour un environnement de developpement simple :

```bash
cd public
php -S localhost:8000
```

Acces a l'application : `http://localhost:8000`

#### Option D : Configuration Apache avec Virtual Host

Creez un fichier de configuration Virtual Host :

**Linux/macOS** : `/etc/apache2/sites-available/klaxon.conf`
**Windows (XAMPP)** : `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

```apache
<VirtualHost *:80>
    ServerName klaxon.local
    DocumentRoot "C:/xampp/htdocs/covoiturage-entreprise/public"

    <Directory "C:/xampp/htdocs/covoiturage-entreprise/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Ajoutez dans votre fichier hosts :
- Windows : `C:\Windows\System32\drivers\etc\hosts`
- Linux/macOS : `/etc/hosts`

```
127.0.0.1    klaxon.local
```

Redemarrez Apache et accedez a `http://klaxon.local`

### 7. Verification de l'installation

#### 7.1. Tester l'acces a l'application

Ouvrez votre navigateur et accedez a l'URL configuree. Vous devriez voir la page d'accueil avec la liste des trajets disponibles.

#### 7.2. Tester la connexion

Cliquez sur le bouton "Connexion" et utilisez les identifiants suivants :

**Compte administrateur :**
- Email : `admin@entreprise.fr`
- Mot de passe : `password123`

**Compte utilisateur (exemple) :**
- Email : `jean.dupont@entreprise.fr`
- Mot de passe : `password123`

#### 7.3. Executer les tests unitaires

Verifiez que les tests passent correctement :

```bash
vendor/bin/phpunit
```

Vous devriez voir un resultat similaire a :

```
PHPUnit 9.5.x by Sebastian Bergmann and contributors.

...........                                                       11 / 11 (100%)

Time: 00:00.234, Memory: 10.00 MB

OK (11 tests, 25 assertions)
```

#### 7.4. Executer l'analyse statique PHPStan

Verifiez la qualite du code :

```bash
vendor/bin/phpstan analyse app --level=5
```

## Structure des donnees initiales

Apres l'installation, votre base de donnees contient :

- **21 utilisateurs** : 1 administrateur + 20 employes
- **12 agences** : Paris, Lyon, Marseille, Toulouse, Nice, Nantes, Strasbourg, Montpellier, Bordeaux, Lille, Rennes, Reims
- **0 trajet** : Les trajets seront crees par les utilisateurs

## Comptes de test disponibles

Tous les utilisateurs de test utilisent le mot de passe : `password123`

### Administrateur
- admin@entreprise.fr

### Employes (exemples)
- jean.dupont@entreprise.fr
- marie.martin@entreprise.fr
- pierre.bernard@entreprise.fr
- sophie.dubois@entreprise.fr

## Commandes utiles

### Developpement

```bash
# Compiler le Sass
npm run build

# Compiler le Sass avec surveillance des modifications
npm run sass:watch

# Lancer le serveur PHP integre
php -S localhost:8000 -t public

# Executer les tests
vendor/bin/phpunit

# Executer PHPStan
vendor/bin/phpstan analyse app --level=5
```

### Production

```bash
# Compiler les assets en mode compresse
npm run build

# Optimiser l'autoloader Composer
composer dump-autoload --optimize
```

## Resolution des problemes courants

### Erreur de connexion a la base de donnees

**Symptome** : Message d'erreur lors de l'acces a l'application

**Solution** :
1. Verifiez que MySQL est bien demarre
2. Verifiez les parametres dans `config/database.php`
3. Assurez-vous que la base `covoiturage_entreprise` existe
4. Verifiez les droits de l'utilisateur MySQL

### Erreur 404 sur les routes

**Symptome** : Seule la page d'accueil fonctionne

**Solution** :
1. Verifiez que le fichier `.htaccess` existe dans le dossier `public/`
2. Assurez-vous que `mod_rewrite` est active dans Apache
3. Verifiez que `AllowOverride All` est configure pour votre repertoire

### Les styles ne s'appliquent pas

**Symptome** : L'application s'affiche sans mise en forme

**Solution** :
1. Verifiez que `npm install` a bien ete execute
2. Compilez les assets avec `npm run build`
3. Verifiez que le fichier `assets/css/app.css` existe
4. Verifiez les chemins dans le layout

### Erreur lors de l'execution des tests

**Symptome** : PHPUnit renvoie des erreurs

**Solution** :
1. Verifiez que la base de donnees de test est accessible
2. Assurez-vous que les dependances dev sont installees : `composer install`
3. Verifiez les droits d'ecriture sur le dossier `tests/`

## Support

Pour toute question ou probleme supplementaire, consultez :
- Le fichier README.md
- La documentation du code (DocBlock)
- Les commentaires dans les fichiers de configuration

## Prochaines etapes

Apres l'installation reussie :

1. Familiarisez-vous avec l'interface en tant qu'utilisateur simple
2. Testez les fonctionnalites d'administration
3. Creez quelques trajets de test
4. Explorez le code source pour comprendre l'architecture
5. Consultez la documentation MCD et MLD dans le dossier `docs/`
