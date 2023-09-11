<?php require_once MAIN_DIR . '/templates/admin/header.php'; ?>

<form method="POST" action="/admin/actors/save" class="col-md-4 mt-4">
    <input type="hidden" name="id" value="<?= $actor->id ?>">

    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="first-name" name="first_name" placeholder="First Name" title="First Name" value="<?= $actor->first_name ?>">
        <label for="first-name">First Name</label>
    </div>

    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="last-name" name="last_name" placeholder="Last Name" title="Last Name" value="<?= $actor->last_name ?>">
        <label for="last-name">Last Name</label>
    </div>

    <button class="btn btn-primary mt-4" type="submit">Save</button>
    <a class="btn btn-danger mt-4" href="/admin/actors/delete/<?= $actor->id ?>">Delete</a>

</form>

<?php require_once MAIN_DIR . '/templates/admin/footer.php';
