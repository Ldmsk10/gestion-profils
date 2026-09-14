<?php
$pageTitle = $pageTitle ?? 'Profils';
$flash = getFlash();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Annuaire de profils développé en PHP et MySQL.">
    <title><?= e($pageTitle) ?> | Profilio</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container nav-bar">
            <a class="brand" href="index.php" aria-label="Accueil Profilio">
                <span class="brand-mark">P</span>
                <span>Profilio</span>
            </a>
            <nav aria-label="Navigation principale">
                <a href="index.php">Les profils</a>
                <a class="button button-small" href="add.php">Ajouter un profil</a>
            </nav>
        </div>
    </header>

    <main class="container page-content">
        <?php if ($flash !== null): ?>
            <div class="alert alert-<?= e($flash['type']) ?>" role="status">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>
