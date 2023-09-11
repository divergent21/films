<?php require_once __DIR__ . '/header.php'; ?>

<main class="container">
    <form action="/search" method="GET" class="mt-4">
        <div class="row">
            <div class="col-9">
                <input 
                    name="s"
                    type="search"
                    class="form-control" 
                    id="exampleDataList" 
                    placeholder="Type to search..."
                    value="<?= $query ?? '' ?: '' ?>"
                >
            </div>
            <div class="col-2">
                <select name="stype" class="form-select">
                    <?php 
                    $stype = $_GET['stype'] ?? '';
                    ?>

                    <option 
                        value="all"
                        <?= 
                        ! in_array(
                            $stype, 
                            ['films', 'actors', 'films_by_actors']
                        ) 
                        ? 'selected' 
                        : '' 
                        ?>
                    >
                        All
                    </option>

                    <option 
                        value="films"
                        <?= $stype === 'films' ? 'selected' : '' ?>
                    >
                        Films
                    </option>
                    <option 
                        value="actors"
                        <?= $stype === 'actors' ? 'selected' : '' ?>
                    >
                        Actors
                    </option>
                    <option 
                        value="films_by_actors"
                        <?= $stype === 'films_by_actors' ? 'selected' : '' ?>
                    >
                        Films By Actors
                    </option>
                </select>
            </div>
            <div class="col-1">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <?php if (! empty($query ?? '')): ?>
        <p class="h5 mt-4">Results for <b><?= $query ?>:</b></p>
    <?php endif; ?>

    <?php if (! empty($films)): ?>
        <h4 class="mt-4">Films</h4>
        <div class="list-group">
            <?php foreach ($films as $film): ?>
                <a 
                    href="/films/<?= $film->id ?>" 
                    class="list-group-item list-group-item-action"
                >
                    <?= $film->title ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (! empty($actors)): ?>
        <h4 class="mt-4">Actors</h4>
        <?php foreach ($actors as $actor): ?>
            <a 
                href="/actors/<?= $actor->id ?>" 
                class="list-group-item list-group-item-action"
            >
                <?= $actor->title() ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (! empty($films_by_actors)): ?>
        <h4 class="mt-4">Films by actors</h4>
        <?php foreach ($films_by_actors as $film): ?>
            <a 
                href="/films/<?= $film->id ?>" 
                class="list-group-item list-group-item-action"
            >
                <?= $film->title ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (
        empty($films) && 
        empty($actors) && 
        empty($films_by_actors) &&
        ! empty($query)
    ): ?>
        <div class="alert alert-notify">
            <b>Nothing found :(</b><br>
            Try change the query or type search.
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/footer.php';