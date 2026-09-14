<?php

// Valeurs locales Laragon. Les variables d'environnement permettent
// de changer la connexion sans enregistrer de mot de passe dans Git.
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: 'gestion_profils';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

// utf8mb4 prend en charge les accents et les emojis.
$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $password, [
        // Une erreur SQL déclenche une exception.
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Les résultats seront accessibles par nom de colonne.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // MySQL prépare lui-même les requêtes paramétrées.
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $exception) {
    // Les détails techniques restent dans les journaux du serveur.
    error_log($exception->getMessage());
    http_response_code(500);
    exit('Connexion à la base de données impossible.');
}
