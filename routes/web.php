<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Servers pillar page
Route::get('/servers', function () {
    return view('servers');
});

// Get all servers
Route::get('/servers', [App\Http\Controllers\ServersController::class, 'index']);
Route::get('/ajax/servers', [App\Http\Controllers\ServersController::class, 'server_list']);


// Route::get('/api/servers/search', [App\Http\Controllers\ServersController::class, 'search']);
