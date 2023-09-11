<?php require_once MAIN_DIR . '/templates/header.php'; ?>

<main>
    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">Actors</h1>
            </div>
        </div>
    </section>

    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($actors as $actor): ?>
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4><?= $actor->title() ?></h4>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="/actors/<?= $actor->id ?>" class="btn btn-sm btn-outline-secondary">View</a>
                                    <!-- <button type="button" class="btn btn-sm btn-outline-secondary">Active</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <?= \Divergent\Films\Models\Actor::render_pagination(9) ?>
    </div>
</main>

<?php require_once MAIN_DIR . '/templates/footer.php';