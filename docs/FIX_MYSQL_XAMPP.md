# Guide de reparation MySQL XAMPP

## Probleme
MySQL ne demarre plus dans XAMPP avec l'erreur :
- "MySQL server has gone away"
- "Aucune connexion n'a pu etre etablie"

## Solution 1 : Verifier qu'un autre service n'utilise pas le port 3306

### Etape 1 : Identifier le processus qui utilise le port 3306

Ouvrez l'Invite de commandes (CMD) en tant qu'administrateur et tapez :

```cmd
netstat -ano | findstr :3306
```

Si vous voyez une ligne, notez le dernier numero (PID).

### Etape 2 : Arreter le processus

```cmd
taskkill /PID [numero_du_PID] /F
```

Exemple : `taskkill /PID 1234 /F`

### Etape 3 : Redemarrer MySQL dans XAMPP

1. Ouvrez le panneau de controle XAMPP
2. Cliquez sur "Start" pour MySQL

## Solution 2 : Changer le port MySQL (si la solution 1 ne fonctionne pas)

### Etape 1 : Editer le fichier my.ini

1. Ouvrez le panneau de controle XAMPP
2. Cliquez sur "Config" a cote de MySQL
3. Selectionnez "my.ini"
4. Cherchez les lignes suivantes et modifiez le port :

```ini
[mysqld]
port=3306
```

Changez en :

```ini
[mysqld]
port=3307
```

5. Cherchez aussi cette ligne et modifiez-la :

```ini
[client]
port=3306
```

Changez en :

```ini
[client]
port=3307
```

6. Sauvegardez le fichier

### Etape 2 : Editer le fichier config.inc.php de phpMyAdmin

1. Ouvrez `C:\xampp\phpMyAdmin\config.inc.php`
2. Cherchez la ligne :

```php
$cfg['Servers'][$i]['port'] = '3306';
```

3. Changez en :

```php
$cfg['Servers'][$i]['port'] = '3307';
```

4. Sauvegardez

### Etape 3 : Modifier la configuration de votre application

Editez `config/database.php` et ajoutez le port :

```php
return [
    'host' => 'localhost:3307',  // Ajoutez :3307
    'dbname' => 'covoiturage_entreprise',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
];
```

### Etape 4 : Redemarrer XAMPP

1. Fermez completement XAMPP
2. Rouvrez-le en tant qu'administrateur (clic droit > Executer en tant qu'administrateur)
3. Demarrez Apache
4. Demarrez MySQL

## Solution 3 : Reinitialiser MySQL (ATTENTION : perte de donnees)

### Si vous avez une sauvegarde de votre base de donnees

1. Arretez MySQL dans XAMPP
2. Renommez le dossier `C:\xampp\mysql\data` en `data_old`
3. Copiez le dossier `C:\xampp\mysql\backup` et renommez-le en `data`
4. Redemarrez MySQL
5. Reimportez votre base de donnees depuis les fichiers SQL du projet

## Solution 4 : Reinstaller XAMPP (dernier recours)

1. Sauvegardez votre base de donnees (si accessible)
2. Sauvegardez vos projets dans `C:\xampp\htdocs`
3. Desinstallez XAMPP
4. Telechargez la derniere version sur https://www.apachefriends.org
5. Reinstallez XAMPP
6. Restaurez vos projets et bases de donnees

## Verification

Apres avoir applique une solution, verifiez :

1. MySQL demarre correctement dans XAMPP (voyant vert)
2. phpMyAdmin est accessible : http://localhost/phpmyadmin
3. Votre application fonctionne : http://localhost/covoiturage-entreprise/public/

## En cas d'echec

Si aucune solution ne fonctionne :

1. Verifiez les logs : `C:\xampp\mysql\data\mysql_error.log`
2. Cherchez l'erreur specifique
3. Consultez les forums XAMPP ou StackOverflow avec le message d'erreur exact
