<form class="profile-form card" method="post" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">

    <?php if (!empty($errors['form'])): ?>
        <div class="alert alert-error" role="alert"><?= e($errors['form']) ?></div>
    <?php endif; ?>

    <div class="form-grid">
        <div class="field">
            <label for="prenom">Prénom <span aria-hidden="true">*</span></label>
            <input id="prenom" name="prenom" type="text" maxlength="100" autocomplete="given-name" required value="<?= e($data['prenom']) ?>">
            <?php if (isset($errors['prenom'])): ?><p class="field-error"><?= e($errors['prenom']) ?></p><?php endif; ?>
        </div>

        <div class="field">
            <label for="nom">Nom <span aria-hidden="true">*</span></label>
            <input id="nom" name="nom" type="text" maxlength="100" autocomplete="family-name" required value="<?= e($data['nom']) ?>">
            <?php if (isset($errors['nom'])): ?><p class="field-error"><?= e($errors['nom']) ?></p><?php endif; ?>
        </div>

        <div class="field">
            <label for="email">E-mail <span aria-hidden="true">*</span></label>
            <input id="email" name="email" type="email" maxlength="255" autocomplete="email" required value="<?= e($data['email']) ?>">
            <?php if (isset($errors['email'])): ?><p class="field-error"><?= e($errors['email']) ?></p><?php endif; ?>
        </div>

        <div class="field">
            <label for="age">Âge <span aria-hidden="true">*</span></label>
            <input id="age" name="age" type="number" min="1" max="120" inputmode="numeric" required value="<?= e($data['age']) ?>">
            <?php if (isset($errors['age'])): ?><p class="field-error"><?= e($errors['age']) ?></p><?php endif; ?>
        </div>

        <div class="field">
            <label for="ville">Ville <span aria-hidden="true">*</span></label>
            <input id="ville" name="ville" type="text" maxlength="100" autocomplete="address-level2" required value="<?= e($data['ville']) ?>">
            <?php if (isset($errors['ville'])): ?><p class="field-error"><?= e($errors['ville']) ?></p><?php endif; ?>
        </div>

        <div class="field">
            <label for="statut">Statut <span aria-hidden="true">*</span></label>
            <input id="statut" name="statut" type="text" maxlength="100" placeholder="Ex. Développeuse web" required value="<?= e($data['statut']) ?>">
            <?php if (isset($errors['statut'])): ?><p class="field-error"><?= e($errors['statut']) ?></p><?php endif; ?>
        </div>
    </div>

    <div class="field">
        <label for="hobbies">Hobbies</label>
        <input id="hobbies" name="hobbies" type="text" maxlength="500" placeholder="Ex. photographie, randonnée, lecture" value="<?= e($data['hobbies']) ?>">
        <p class="field-help">Sépare plusieurs hobbies par une virgule.</p>
        <?php if (isset($errors['hobbies'])): ?><p class="field-error"><?= e($errors['hobbies']) ?></p><?php endif; ?>
    </div>

    <div class="field">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5" maxlength="2000" placeholder="Présentez ce profil en quelques phrases."><?= e($data['description']) ?></textarea>
        <?php if (isset($errors['description'])): ?><p class="field-error"><?= e($errors['description']) ?></p><?php endif; ?>
    </div>

    <div class="field">
        <label for="photo">Photo de profil</label>
        <?php if (!empty($data['photo'])): ?>
            <img class="photo-preview" src="<?= e($data['photo']) ?>" alt="Photo actuelle de <?= e($data['prenom']) ?>">
        <?php endif; ?>
        <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp">
        <p class="field-help">JPEG, PNG ou WebP, 2 Mo maximum.</p>
        <?php if (isset($errors['photo'])): ?><p class="field-error"><?= e($errors['photo']) ?></p><?php endif; ?>
    </div>

    <div class="form-actions">
        <button class="button" type="submit"><?= e($submitLabel) ?></button>
        <a class="button button-secondary" href="index.php">Annuler</a>
    </div>
</form>
