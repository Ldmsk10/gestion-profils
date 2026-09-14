<?php

declare(strict_types=1);

const MAX_PHOTO_SIZE = 2 * 1024 * 1024;

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function validateProfile(array $input): array
{
    $data = [
        'nom' => trim((string) ($input['nom'] ?? '')),
        'prenom' => trim((string) ($input['prenom'] ?? '')),
        'email' => trim((string) ($input['email'] ?? '')),
        'age' => trim((string) ($input['age'] ?? '')),
        'ville' => trim((string) ($input['ville'] ?? '')),
        'statut' => trim((string) ($input['statut'] ?? '')),
        'hobbies' => trim((string) ($input['hobbies'] ?? '')),
        'description' => trim((string) ($input['description'] ?? '')),
    ];

    $errors = [];
    $requiredFields = [
        'nom' => 'Le nom est obligatoire.',
        'prenom' => 'Le prénom est obligatoire.',
        'email' => 'L’adresse e-mail est obligatoire.',
        'age' => 'L’âge est obligatoire.',
        'ville' => 'La ville est obligatoire.',
        'statut' => 'Le statut est obligatoire.',
    ];

    foreach ($requiredFields as $field => $message) {
        if ($data[$field] === '') {
            $errors[$field] = $message;
        }
    }

    foreach (['nom', 'prenom', 'ville', 'statut'] as $field) {
        if ($data[$field] !== '' && mb_strlen($data[$field]) > 100) {
            $errors[$field] = 'Ce champ ne peut pas dépasser 100 caractères.';
        }
    }

    if ($data['email'] !== '' && (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($data['email']) > 255)) {
        $errors['email'] = 'Saisissez une adresse e-mail valide.';
    }

    $age = filter_var($data['age'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 120],
    ]);
    if ($data['age'] !== '' && $age === false) {
        $errors['age'] = 'L’âge doit être compris entre 1 et 120 ans.';
    } elseif ($age !== false) {
        $data['age'] = (string) $age;
    }

    if (mb_strlen($data['hobbies']) > 500) {
        $errors['hobbies'] = 'Les hobbies ne peuvent pas dépasser 500 caractères.';
    }

    if (mb_strlen($data['description']) > 2000) {
        $errors['description'] = 'La description ne peut pas dépasser 2 000 caractères.';
    }

    return [$data, $errors];
}

function saveUploadedPhoto(array $file, ?string $currentPhoto = null): array
{
    $errorCode = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($errorCode === UPLOAD_ERR_NO_FILE) {
        return [$currentPhoto, null];
    }

    if ($errorCode !== UPLOAD_ERR_OK) {
        return [$currentPhoto, 'Le téléchargement de la photo a échoué.'];
    }

    if ((int) ($file['size'] ?? 0) > MAX_PHOTO_SIZE) {
        return [$currentPhoto, 'La photo ne doit pas dépasser 2 Mo.'];
    }

    $temporaryPath = (string) ($file['tmp_name'] ?? '');
    $mimeType = $temporaryPath !== '' ? (new finfo(FILEINFO_MIME_TYPE))->file($temporaryPath) : false;
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($extensions[$mimeType])) {
        return [$currentPhoto, 'Choisissez une image JPEG, PNG ou WebP.'];
    }

    $uploadsDirectory = dirname(__DIR__) . '/uploads';
    if (!is_dir($uploadsDirectory) && !mkdir($uploadsDirectory, 0755, true) && !is_dir($uploadsDirectory)) {
        return [$currentPhoto, 'Le dossier des photos ne peut pas être créé.'];
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
    if (!move_uploaded_file($temporaryPath, $uploadsDirectory . '/' . $filename)) {
        return [$currentPhoto, 'La photo ne peut pas être enregistrée.'];
    }

    if ($currentPhoto !== null) {
        deletePhoto($currentPhoto);
    }

    return ['uploads/' . $filename, null];
}

function deletePhoto(?string $photo): void
{
    if ($photo === null || !str_starts_with($photo, 'uploads/')) {
        return;
    }

    $path = dirname(__DIR__) . '/' . $photo;
    if (is_file($path)) {
        unlink($path);
    }
}

function findProfile(PDO $pdo, int $id): ?array
{
    $statement = $pdo->prepare('SELECT * FROM profils WHERE id = :id');
    $statement->execute(['id' => $id]);
    $profile = $statement->fetch();

    return $profile ?: null;
}

function emailAlreadyExists(PDO $pdo, string $email, ?int $ignoredId = null): bool
{
    $sql = 'SELECT COUNT(*) FROM profils WHERE email = :email';
    $parameters = ['email' => $email];

    if ($ignoredId !== null) {
        $sql .= ' AND id != :id';
        $parameters['id'] = $ignoredId;
    }

    $statement = $pdo->prepare($sql);
    $statement->execute($parameters);

    return (int) $statement->fetchColumn() > 0;
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrfIsValid(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return $flash;
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}
