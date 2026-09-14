<?php

require __DIR__ . '/config/bootstrap.php';

$profiles = $pdo->query('SELECT * FROM profils ORDER BY id DESC')->fetchAll();
$pageTitle = 'Annuaire des profils';

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div>
        <p class="eyebrow">Annuaire professionnel</p>
        <h1>Des profils, des parcours, des talents.</h1>
        <p class="hero-text">Retrouvez les membres de l’annuaire et découvrez leurs centres d’intérêt.</p>
    </div>
    <a class="button" href="add.php">Créer un profil</a>
</section>

<div class="section-heading">
    <div>
        <h2>Les profils</h2>
        <p><?= count($profiles) ?> profil<?= count($profiles) > 1 ? 's' : '' ?> enregistré<?= count($profiles) > 1 ? 's' : '' ?></p>
    </div>
</div>

<?php if ($profiles === []): ?>
    <section class="empty-state card">
        <div class="empty-icon" aria-hidden="true">👤</div>
        <h2>L’annuaire est encore vide</h2>
        <p>Ajoutez un premier profil pour commencer à construire votre annuaire.</p>
        <a class="button" href="add.php">Ajouter le premier profil</a>
    </section>
<?php else: ?>
    <div class="profile-grid">
        <?php foreach ($profiles as $profile): ?>
            <article class="profile-card card">
                <div class="profile-photo-wrap">
                    <?php if ($profile['photo']): ?>
                        <img class="profile-photo" src="<?= e($profile['photo']) ?>" alt="Photo de <?= e($profile['prenom'] . ' ' . $profile['nom']) ?>">
                    <?php else: ?>
                        <div class="profile-photo profile-initials" aria-hidden="true">
                            <?= e(mb_strtoupper(mb_substr($profile['prenom'], 0, 1) . mb_substr($profile['nom'], 0, 1))) ?>
                        </div>
                    <?php endif; ?>
                    <span class="status-badge"><?= e($profile['statut']) ?></span>
                </div>

                <div class="profile-content">
                    <h3><?= e($profile['prenom'] . ' ' . $profile['nom']) ?></h3>
                    <p class="profile-meta"><?= (int) $profile['age'] ?> ans · <?= e($profile['ville']) ?></p>
                    <a class="profile-email" href="mailto:<?= e($profile['email']) ?>"><?= e($profile['email']) ?></a>

                    <?php if ($profile['description']): ?>
                        <p class="profile-description"><?= nl2br(e($profile['description'])) ?></p>
                    <?php endif; ?>

                    <?php if ($profile['hobbies']): ?>
                        <p class="hobbies"><strong>Hobbies :</strong> <?= e($profile['hobbies']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="profile-actions">
                    <a class="button button-secondary button-small" href="edit.php?id=<?= (int) $profile['id'] ?>">Modifier</a>
                    <a class="text-danger" href="delete.php?id=<?= (int) $profile['id'] ?>">Supprimer</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
