<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('projects', \App\Http\Controllers\ProjectsController::class);
    Route::resource('projects.tasks', \App\Http\Controllers\ProjectTaskController::class);
    Route::resource('projects.invitations', \App\Http\Controllers\ProjectInvitationsController::class );
//    Route::post('projects.invitations', \App\Http\Controllers\ProjectInvatationsController::class);
});
