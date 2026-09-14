<?php

require __DIR__ . '/config/bootstrap.php';

$data = [
    'nom' => '', 'prenom' => '', 'email' => '', 'age' => '',
    'ville' => '', 'statut' => '', 'hobbies' => '', 'description' => '', 'photo' => null,
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$data, $errors] = validateProfile($_POST);
    $data['photo'] = null;

    if (!csrfIsValid($_POST['csrf_token'] ?? null)) {
        $errors['form'] = 'La session a expiré. Rechargez la page et réessayez.';
    }

    if (!isset($errors['email']) && emailAlreadyExists($pdo, $data['email'])) {
        $errors['email'] = 'Cette adresse e-mail est déjà utilisée.';
    }

    if ($errors === []) {
        [$photo, $photoError] = saveUploadedPhoto($_FILES['photo'] ?? []);
        if ($photoError !== null) {
            $errors['photo'] = $photoError;
        } else {
            $data['photo'] = $photo;
        }
    }

    if ($errors === []) {
        $statement = $pdo->prepare(
            'INSERT INTO profils (nom, prenom, email, age, ville, statut, hobbies, description, photo)
             VALUES (:nom, :prenom, :email, :age, :ville, :statut, :hobbies, :description, :photo)'
        );
        $statement->execute($data);
        setFlash('success', 'Le profil a bien été créé.');
        redirect('index.php');
    }
}

$pageTitle = 'Ajouter un profil';
$submitLabel = 'Créer le profil';
require __DIR__ . '/includes/header.php';
?>

<header class="page-heading">
    <p class="eyebrow">Nouveau membre</p>
    <h1>Ajouter un profil</h1>
    <p>Les champs marqués d’un astérisque sont obligatoires.</p>
</header>

<?php require __DIR__ . '/includes/profile-form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
