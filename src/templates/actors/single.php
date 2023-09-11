<?php require_once MAIN_DIR . '/templates/header.php'; ?>

<main>
    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light"><strong><?= $actor->last_name ?></strong> <?= $actor->first_name ?></h1>
                <p>Films (<?= count($actor->films()) ?>)</p>
            </div>
        </div>
    </section>

    <section class="container">
        <h2>Films</h2>

        <ul class="list-group list-group-flush">
            <?php foreach ($actor->films() as $film): ?>
                <li class="list-group-item">
                    <a class="list-group-item" href="/films/<?= $film->id ?>">
                        <?= $film->title ?> (<?= $film->year() ?>)
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>

<?php require_once MAIN_DIR . '/templates/footer.php';