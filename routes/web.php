<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', App\Http\Controllers\RootAction::class);
Route::get('/releases', App\Http\Controllers\Releases\IndexAction::class);
Route::get('/releases.json', App\Http\Controllers\Releases\JsonAction::class);
Route::get('/releases/latest.json', App\Http\Controllers\Releases\LatestJsonAction::class);
Route::get('/downloads/latest.zip', App\Http\Controllers\Downloads\DownloadLatestZipAction::class);
Route::get('/downloads/PortalDots-{version}.zip', App\Http\Controllers\Downloads\DownloadZipAction::class)->where('version', '[A-Za-z0-9\.-]+')->name('downloads.zip');
