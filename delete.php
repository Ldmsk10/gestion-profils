<?php

require __DIR__ . '/config/bootstrap.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$profile = $id ? findProfile($pdo, $id) : null;

if ($profile === null) {
    http_response_code(404);
    $pageTitle = 'Profil introuvable';
    require __DIR__ . '/includes/header.php';
    echo '<section class="empty-state card"><h1>Profil introuvable</h1><p>Ce profil n’existe pas ou a déjà été supprimé.</p><a class="button" href="index.php">Retour à l’annuaire</a></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfIsValid($_POST['csrf_token'] ?? null)) {
        $error = 'La session a expiré. Rechargez la page et réessayez.';
    } else {
        $statement = $pdo->prepare('DELETE FROM profils WHERE id = :id');
        $statement->execute(['id' => (int) $profile['id']]);
        deletePhoto($profile['photo']);
        setFlash('success', 'Le profil a bien été supprimé.');
        redirect('index.php');
    }
}

$pageTitle = 'Supprimer le profil';
require __DIR__ . '/includes/header.php';
?>

<section class="confirm-card card">
    <div class="danger-icon" aria-hidden="true">!</div>
    <p class="eyebrow">Confirmation</p>
    <h1>Supprimer ce profil ?</h1>
    <p>Vous êtes sur le point de supprimer <strong><?= e($profile['prenom'] . ' ' . $profile['nom']) ?></strong>. Cette action est définitive.</p>

    <?php if ($error !== null): ?>
        <div class="alert alert-error" role="alert"><?= e($error) ?></div>
    <?php endif; ?>

    <form class="confirm-actions" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <button class="button button-danger" type="submit">Oui, supprimer</button>
        <a class="button button-secondary" href="index.php">Annuler</a>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
