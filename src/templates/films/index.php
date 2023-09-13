<?php require_once MAIN_DIR . '/templates/header.php'; ?>

<main>
    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">Films</h1>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <label>Sort order</label>
            <div class="col-2">
                <form action="/films" method="GET" class="row">
                    <div class="col-auto">
                        <select name="sort" class="form-control form-select mb-2">
                            <option <?= $_GET['sort'] === 'ASC' ? 'selected' : '' ?> value="ASC">A-Z</option>
                            <option <?= $_GET['sort'] === 'DESC' ? 'selected' : '' ?> value="DESC">Z-A</option>
                        </select>
                    </div>
                    

                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-secondary">Show</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($films as $film): ?>
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4><?= $film->title ?></h4>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="/films/<?= $film->id ?>" class="btn btn-sm btn-outline-secondary">View</a>
                                    <!-- <button type="button" class="btn btn-sm btn-outline-secondary">Active</button> -->
                                </div>
                                <small class="text-muted"><?= $film->year() ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <?= \Divergent\Films\Models\Film::render_pagination(9) ?>
    </div>
</main>

<?php require_once MAIN_DIR . '/templates/footer.php';