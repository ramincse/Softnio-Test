<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

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

Route::middleware(['auth:sanctum,web', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

/**
 * Admin
 */
Route::group(['prefix'=>'admin', 'middleware'=>['admin:admin']], function(){
    Route::get('/login', [AdminController::class, 'loginForm']);
    Route::post('/login', [AdminController::class, 'store'])->name('admin.login');
});

Route::middleware(['auth:sanctum,admin', 'verified'])->get('/admin/dashboard', function () {
    return view('backend.dashboard');
})->name('dashboard');

//Admin Logout
Route::get('/admin/logout', [AdminController::class, 'destroy'])->name('admin.logout');



/**
 * Route For Category 
 */
Route::resource('/category', 'App\Http\Controllers\Backend\CategoryController');
//Switcher Update
Route::get('/category/status-update/{status_id}', 'App\Http\Controllers\Backend\CategoryController@statusUpdateCategory');


/**
 * Route For Course 
 */
Route::resource('/course', 'App\Http\Controllers\Backend\CourseController');
//Switcher Update
Route::get('/course/status-update/{status_id}', 'App\Http\Controllers\Backend\CourseController@statusUpdateCourse');


/**
 * Route For Course Content Post
 */
Route::resource('/post', 'App\Http\Controllers\Backend\PostController');
//Switcher Update
Route::get('/course/content/status-update/{status_id}', 'App\Http\Controllers\Backend\PostController@statusUpdatePost');
//
Route::get('/multi/image/{id}', 'App\Http\Controllers\Backend\PostController@multiImageShow')->name('multi.image');
Route::post('/multi/image/update', 'App\Http\Controllers\Backend\PostController@multiImageUpdate')->name('multi.image.update');
Route::get('/multi/image/delete/{id}', 'App\Http\Controllers\Backend\PostController@deleteMultiImage')->name('multi.image.delete');
