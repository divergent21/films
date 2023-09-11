<?php require_once MAIN_DIR . '/templates/admin/header.php'; ?>

<div class="list-group">
    <?php foreach ($actors as $actor): ?>
        <a href="/admin/actors/<?= $actor->id ?>" class="list-group-item list-group-item-action"><?= $actor->title() ?></a>
    <?php endforeach; ?>
</div>

<?= \Divergent\Films\Models\Actor::render_pagination(); ?>

<?php require_once MAIN_DIR . '/templates/admin/footer.php';