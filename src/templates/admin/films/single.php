<?php require_once MAIN_DIR . '/templates/admin/header.php'; ?>

<form method="POST" action="/admin/films/save" class="col-md-4 mt-4">
    <input type="hidden" name="id" value="<?= $film->id ?>">

    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="title" name="title" placeholder="Film title" title="Title" value="<?= $film->title ?>">
        <label for="title">Film title</label>
    </div>

    <div class="form-floating mb-3">
        <input type="number" class="form-control" name="year" min="1900" max="<?= date('Y') ?>" maxlength="4" id="year" placeholder="Film year" title="Year" value="<?= $film->year() ?>">
        <label for="year">Film year</label>
    </div>

    <label for="">
        Film format

        <select name="format" class="form-select" aria-label="Film format">
            <?php
            $available_formats = ['VHS', 'DVD', 'Blu-ray'];
            ?>
            <option disabled <?= in_array($film->format, $available_formats) ? '' : 'selected' ?>>Open this select menu</option>
            <?php foreach ($available_formats as $af) : ?>
                <option <?= $film->format == $af ? 'checked' : '' ?> value="<?= $af ?>"><?= $af ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <?php
    $film_actors_ids = array_map(function ($actor) {
        return $actor->id;
    }, $film->actors());
    ?>

    <h5 class="mt-4">Actors</h5>

    <?php foreach ($actors as $actor) : ?>
        <div class="form-check">
            <input name="actors[]" class="form-check-input" type="checkbox" value="<?= $actor->id ?>" id="actor-<?= $actor->id ?>" <?= in_array($actor->id, $film_actors_ids) ? 'checked' : '' ?>>
            <label class="form-check-label" for="actor-<?= $actor->id ?>">
                <?= $actor->title() ?>
            </label>
        </div>
    <?php endforeach; ?>

    <button class="btn btn-primary mt-4" type="submit">Save</button>
    <a class="btn btn-danger mt-4" href="/admin/films/delete/<?= $film->id ?>">Delete</a>

</form>

<?php require_once MAIN_DIR . '/templates/admin/footer.php';
