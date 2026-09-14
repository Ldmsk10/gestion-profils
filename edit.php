<?php

require __DIR__ . '/config/bootstrap.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$profile = $id ? findProfile($pdo, $id) : null;

if ($profile === null) {
    http_response_code(404);
    $pageTitle = 'Profil introuvable';
    require __DIR__ . '/includes/header.php';
    echo '<section class="empty-state card"><h1>Profil introuvable</h1><p>Ce profil n’existe pas ou a été supprimé.</p><a class="button" href="index.php">Retour à l’annuaire</a></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$data = $profile;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$submittedData, $errors] = validateProfile($_POST);
    $data = array_merge($profile, $submittedData);

    if (!csrfIsValid($_POST['csrf_token'] ?? null)) {
        $errors['form'] = 'La session a expiré. Rechargez la page et réessayez.';
    }

    if (!isset($errors['email']) && emailAlreadyExists($pdo, $data['email'], (int) $profile['id'])) {
        $errors['email'] = 'Cette adresse e-mail est déjà utilisée.';
    }

    if ($errors === []) {
        [$photo, $photoError] = saveUploadedPhoto($_FILES['photo'] ?? [], $profile['photo']);
        if ($photoError !== null) {
            $errors['photo'] = $photoError;
        } else {
            $data['photo'] = $photo;
        }
    }

    if ($errors === []) {
        $statement = $pdo->prepare(
            'UPDATE profils SET nom = :nom, prenom = :prenom, email = :email, age = :age,
             ville = :ville, statut = :statut, hobbies = :hobbies, description = :description, photo = :photo
             WHERE id = :id'
        );
        $statement->execute([
            ...$data,
            'id' => (int) $profile['id'],
        ]);
        setFlash('success', 'Le profil a bien été modifié.');
        redirect('index.php');
    }
}

$pageTitle = 'Modifier le profil';
$submitLabel = 'Enregistrer les modifications';
require __DIR__ . '/includes/header.php';
?>

<header class="page-heading">
    <p class="eyebrow">Mise à jour</p>
    <h1>Modifier <?= e($profile['prenom'] . ' ' . $profile['nom']) ?></h1>
    <p>Modifiez les informations nécessaires puis enregistrez.</p>
</header>

<?php require __DIR__ . '/includes/profile-form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
