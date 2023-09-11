<?php require_once __DIR__ . '/header.php'; ?>

<form method="POST" action="/admin/import_films" class="mt-4" enctype="multipart/form-data">
    <div class="mb-3 col-md-4">
        <label for="formFile" class="form-label">Upload a file with the data.</label>
        <input class="form-control" type="file" name="import_file" id="formFile">
    </div>

    <button class="btn btn-primary mt-4" type="submit">Import</button>
</form>

<?php require_once __DIR__ . '/footer.php';