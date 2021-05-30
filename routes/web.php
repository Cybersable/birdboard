<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('projects', \App\Http\Controllers\ProjectsController::class);
    Route::resource('projects.tasks', \App\Http\Controllers\TaskController::class);
});
