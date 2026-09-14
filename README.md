# Profilio — Annuaire de profils

Profilio est une petite application CRUD réalisée en PHP et MySQL. Elle permet de créer et gérer un annuaire de profils avec photo, coordonnées et présentation. Le projet privilégie un code lisible, une interface responsive et des pratiques adaptées à un premier portfolio de développeur web.

## Fonctionnalités

- affichage responsive de tous les profils ;
- état d’accueil lorsque l’annuaire est vide ;
- création et modification d’un profil ;
- suppression sur une page de confirmation ;
- photo de profil JPEG, PNG ou WebP limitée à 2 Mo ;
- validation côté serveur et messages d’erreur précis ;
- messages de confirmation après chaque action ;
- requêtes SQL préparées avec PDO ;
- protection CSRF des formulaires et échappement des contenus affichés.

## Technologies

- PHP 8.1 ou version ultérieure ;
- MySQL 8 ;
- HTML5 et CSS3, sans framework ;
- PDO avec l’extension `pdo_mysql`.

## Installation avec Laragon

1. Cloner ou placer le projet dans le dossier web de Laragon, par exemple `C:\laragon\www\gestion-profils`.
2. Démarrer **Apache** et **MySQL** depuis Laragon.
3. Importer `database.sql` depuis HeidiSQL, phpMyAdmin ou le terminal :

   ```bash
   mysql -u root < database.sql
   ```

4. Vérifier les identifiants dans `config/database.php`. Les valeurs par défaut correspondent à Laragon : hôte `127.0.0.1`, utilisateur `root`, mot de passe vide.
5. Ouvrir `http://gestion-profils.test` si les hôtes automatiques Laragon sont actifs, ou `http://localhost/gestion-profils`.

Pour lancer le projet sans Apache depuis son dossier :

```bash
php -S localhost:8000
```

Puis ouvrir `http://localhost:8000` dans le navigateur. MySQL doit rester démarré.

## Configuration de la base

La connexion peut aussi être configurée sans modifier le code grâce aux variables d’environnement `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` et `DB_PASSWORD`.

Le fichier `database.sql` crée la base `gestion_profils` et la table `profils`. Les images sont enregistrées dans `uploads/`, tandis que MySQL conserve seulement leur chemin relatif. Les fichiers envoyés sont ignorés par Git.

## Structure du projet

```text
gestion-profils/
├── assets/style.css
├── config/
│   ├── bootstrap.php
│   └── database.php
├── includes/
│   ├── footer.php
│   ├── functions.php
│   ├── header.php
│   └── profile-form.php
├── tests/run.php
├── uploads/.gitkeep
├── add.php
├── database.sql
├── delete.php
├── edit.php
└── index.php
```

## Vérification

Le script suivant teste les principales règles de validation :

```bash
php tests/run.php
```

Pour vérifier rapidement la syntaxe de tous les fichiers PHP avec PowerShell :

```powershell
Get-ChildItem -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

## Sécurité appliquée

Toutes les données SQL dynamiques passent par des requêtes préparées. Les contenus sont échappés avant affichage, les formulaires disposent d’un jeton CSRF et les fichiers sont contrôlés par leur type MIME réel. Les erreurs de connexion détaillées sont écrites dans le journal PHP plutôt qu’affichées aux visiteurs.

## Auteur

Projet réalisé dans le cadre d’un portfolio junior afin de démontrer les bases de PHP, MySQL, du CRUD et de l’intégration responsive.
