<?php require_once MAIN_DIR . '/templates/admin/header.php'; ?>

<form method="POST" action="/admin/films/save" class="col-md-4 mt-4">
    <input type="hidden" name="id" value="<?= $film->id ?>">

    <div class="form-floating mb-3">
        <input required type="text" class="form-control" id="title" name="title" placeholder="Film title" title="Title" value="<?= $film->title ?>">
        <label for="title">Film title</label>
    </div>

    <div class="form-floating mb-3">
        <input required type="number" class="form-control" name="year" min="1900" max="<?= date('Y') ?>" maxlength="4" id="year" placeholder="Film year" title="Year" value="<?= $film->year() ?>">
        <label for="year">Film year</label>
    </div>

    <label for="">
        Film format

        <select required name="format" class="form-select" aria-label="Film format">
            <?php
            $available_formats = ['VHS', 'DVD', 'Blu-ray'];
            ?>
            <?php foreach ($available_formats as $af) : ?>
                <option <?= $film->format === $af ? 'selected' : '' ?> value="<?= $af ?>"><?= $af ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <?php
    $film_actors_ids = array_map(function ($actor) {
        return $actor->id;
    }, $film->actors());
    ?>

    <h5 class="mt-4">Actors</h5>

    <div id="actors-list" style="max-height: 600px; overflow: scroll;">
        <?php foreach ($actors as $actor) : ?>
            <div class="form-check">
                <input name="actors[]" class="form-check-input" type="checkbox" value="<?= $actor->id ?>" id="actor-<?= $actor->id ?>" <?= in_array($actor->id, $film_actors_ids) ? 'checked' : '' ?>>
                <label class="form-check-label" for="actor-<?= $actor->id ?>">
                    <?= $actor->title() ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="btn btn-primary mt-4" type="submit">Save</button>
    <button type="button" data-bs-toggle="modal" data-bs-target="#deleteFilmModal" class="btn btn-danger mt-4">Delete</button>

    <!-- Modal -->
    <div class="modal fade" id="deleteFilmModal" tabindex="-1" aria-labelledby="deleteFilmModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteFilmModalLabel">Delete the film ?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="window.location.href = '/admin/films/delete/<?= $film->id ?>'">Delete</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php require_once MAIN_DIR . '/templates/admin/footer.php';
