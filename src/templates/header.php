<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Films' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <?php global $kernel; ?>

    <header class="p-3 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="/" class="nav-link px-2 <?= $kernel->router()->is_name('main') ? 'text-secondary' : 'text-white' ?>">Home</a></li>
                    <li><a href="/films" class="nav-link px-2 <?= $kernel->router()->is_name('films') ? 'text-secondary' : 'text-white' ?>">Films</a></li>
                    <li><a href="/actors" class="nav-link px-2 <?= $kernel->router()->is_name('actors') ? 'text-secondary' : 'text-white' ?>">Actors</a></li>
                    <li><a href="/search" class="nav-link px-2 <?= $kernel->router()->is_name('search') ? 'text-secondary' : 'text-white' ?>">Search</a></li>
                </ul>

                <div class="text-end">
                    <?php if (\Divergent\Films\Models\Auth::try_get_user()): ?>
                        <a href="/logout" class="btn btn-outline-secondary me-2">Sign out</a>
                        <a href="/admin" class="btn btn-outline-light me-2">Panel</a>
                    <?php else: ?>
                        <a href="/login" class="btn btn-outline-light me-2">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>