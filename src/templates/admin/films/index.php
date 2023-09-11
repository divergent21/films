<?php require_once MAIN_DIR . '/templates/admin/header.php'; ?>

<div class="list-group">
    <?php foreach ($films as $film): ?>
        <a href="/admin/films/<?= $film->id ?>" class="list-group-item list-group-item-action"><?= $film->title ?></a>
    <?php endforeach; ?>
</div>

<?= \Divergent\Films\Models\Film::render_pagination(); ?>

<?php require_once MAIN_DIR . '/templates/admin/footer.php';