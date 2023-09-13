<?php

use Divergent\Films\Core\Facades\Route;

use Divergent\Films\Controllers\FilmsController;
use Divergent\Films\Controllers\Admin\FilmsController as AdminFilmsController;
use Divergent\Films\Controllers\Admin\ActorsController as AdminActorsController;
use Divergent\Films\Controllers\ActorsController;
use Divergent\Films\Controllers\AuthController;
use Divergent\Films\Controllers\AdminController;
use Divergent\Films\Controllers\SearchController;
use Divergent\Films\Controllers\MainController;

// Main page
Route::get('/', [MainController::class, 'index'], 'main');

// Search
Route::get('/search', [SearchController::class, 'index'], 'search');

// Films
Route::get('/films', [FilmsController::class, 'index'], 'films');
Route::get('/films/{id}', [FilmsController::class, 'single'], 'single');

// Actors
Route::get('/actors', [ActorsController::class, 'index'], 'actors');
Route::get('/actors/{id}', [ActorsController::class, 'single'], 'single_actor');

// Auth
Route::get('/login', [AuthController::class, 'login'], 'login');
Route::post('/login', [AuthController::class, 'signin'], 'signin');
Route::get('/logout', [AuthController::class, 'logout'], 'logout');

// Admin
Route::get('/admin', [AdminController::class, 'index'], 'admin', true);
Route::get('/admin/import_films', [AdminController::class, 'import_films'], 'admin_import_films', true);
Route::post('/admin/import_films', [AdminController::class, 'import_films_post'], '', true);

// Admin Films
Route::get('/admin/films', [AdminFilmsController::class, 'index'], 'admin_films', true);
Route::get('/admin/films/{id}', [AdminFilmsController::class, 'single'], 'admin_single_film', true);
Route::get('/admin/films/create', [AdminFilmsController::class, 'new'], 'admin_create_film', true);
Route::post('/admin/films/create', [AdminFilmsController::class, 'create'], 'admin_create_film_post', true);
Route::post('/admin/films/save', [AdminFilmsController::class, 'save'], 'admin_film_save', true);
Route::get('/admin/films/delete/{id}', [AdminFilmsController::class, 'delete'], 'admin_film_delete', true);

// Admin Actors
Route::get('/admin/actors', [AdminActorsController::class, 'index'], 'admin_actors', true);
Route::get('/admin/actors/{id}', [AdminActorsController::class, 'single'], 'admin_single_actor', true);
Route::post('/admin/actors/save', [AdminActorsController::class, 'save'], 'admin_actor_save', true);
Route::get('/admin/actors/delete/{id}', [AdminActorsController::class, 'delete'], 'admin_actor_delete', true);