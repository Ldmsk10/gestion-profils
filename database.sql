-- À importer une seule fois pour préparer le projet.
CREATE DATABASE IF NOT EXISTS gestion_profils
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE gestion_profils;

CREATE TABLE profils (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    age TINYINT UNSIGNED NOT NULL,
    ville VARCHAR(100) NOT NULL,
    statut VARCHAR(100) NOT NULL,
    hobbies TEXT NULL,
    description TEXT NULL,
    -- Chemin relatif de l'image, par exemple uploads/photo.jpg.
    photo VARCHAR(255) NULL,
    CONSTRAINT chk_profils_age CHECK (age <= 120)
) ENGINE=InnoDB;
