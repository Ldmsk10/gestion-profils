<?php

declare(strict_types=1);

require dirname(__DIR__) . '/includes/functions.php';

$failures = 0;

function check(bool $condition, string $message): void
{
    global $failures;

    if (!$condition) {
        $failures++;
        echo "[ECHEC] $message" . PHP_EOL;
        return;
    }

    echo "[OK] $message" . PHP_EOL;
}

$validProfile = [
    'nom' => 'Dupont',
    'prenom' => 'Alice',
    'email' => 'alice@example.com',
    'age' => '28',
    'ville' => 'Lyon',
    'statut' => 'Développeuse web',
    'hobbies' => 'Randonnée, photographie',
    'description' => 'J’aime créer des interfaces simples et accessibles.',
];

[$cleanData, $errors] = validateProfile($validProfile);
check($errors === [], 'un profil valide est accepté');
check($cleanData['email'] === 'alice@example.com', 'les données validées sont retournées');

[$cleanData, $errors] = validateProfile(array_merge($validProfile, [
    'nom' => '   ',
    'email' => 'email-invalide',
    'age' => '0',
]));
check(isset($errors['nom']), 'le nom est obligatoire');
check(isset($errors['email']), 'une adresse e-mail invalide est refusée');
check(isset($errors['age']), 'l’âge doit être compris entre 1 et 120 ans');

[$cleanData, $errors] = validateProfile(array_merge($validProfile, [
    'description' => str_repeat('a', 2001),
]));
check(isset($errors['description']), 'une description trop longue est refusée');

check(e('<script>') === '&lt;script&gt;', 'l’affichage HTML est échappé');

exit($failures === 0 ? 0 : 1);
