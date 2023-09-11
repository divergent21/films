<?php require_once MAIN_DIR . '/templates/header.php'; ?>

<main>
    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light"><?= $film->title ?> <strong>(<?= $film->year() ?>)</strong></h1>
                <p>In (<?= $film->format ?>) format</p>
            </div>
        </div>
    </section>

    <section class="container">
        <h2>Actors</h2>

        <ul class="list-group list-group-flush">
            <?php foreach ($film->actors() as $actor): ?>
                <li class="list-group-item">
                    <a class="list-group-item" href="/actors/<?= $actor->id ?>">
                        <?= $actor->title() ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>

<?php require_once MAIN_DIR . '/templates/footer.php';