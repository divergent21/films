<?php require_once MAIN_DIR . '/templates/admin/header.php'; ?>

<form method="POST" action="/admin/films/create" class="col-md-4 mt-4">
    <div class="form-floating mb-3">
        <input required type="text" maxlength="255" class="form-control" id="title" name="title" placeholder="Film title" title="Title">
        <label for="title">Film title</label>
    </div>

    <div class="form-floating mb-3">
        <input required type="number" class="form-control" name="year" min="1900" max="<?= date('Y') ?>" maxlength="4" id="year" placeholder="Film year" title="Year">
        <label for="year">Film year</label>
    </div>

    <label for="">
        Film format

        <select required name="format" class="form-select" aria-label="Film format">
            <?php
            $available_formats = ['VHS', 'DVD', 'Blu-ray'];
            ?>
            <?php foreach ($available_formats as $af) : ?>
                <option value="<?= $af ?>"><?= $af ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <h5 class="mt-4">Actors</h5>

    <div id="actors-list" style="max-height: 600px; overflow: scroll;">
        <?php foreach ($actors as $actor): ?>
            <div class="form-check">
                <input name="actors[]" class="form-check-input" type="checkbox" value="<?= $actor->id ?>" id="actor-<?= $actor->id ?>">
                <label class="form-check-label" for="actor-<?= $actor->id ?>">
                    <?= $actor->title() ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="btn btn-primary mt-4" type="submit">Save</button>
</form>

<?php require_once MAIN_DIR . '/templates/admin/footer.php';
