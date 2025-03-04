<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
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

Route::get('/', [ViewController::class, 'home']);
Route::get('/about', [ViewController::class, 'about']);
Route::get('/contact', [ViewController::class, 'contact']);
Route::get('/games', [ViewController::class, 'games']);
Route::get('/register', [ViewController::class, 'register']);
Route::get('/verify', [ViewController::class, 'verify']);
Route::get('/login', [ViewController::class, 'login']);
Route::get('/forgotPassword', [ViewController::class, 'forgotPassword']);
Route::get('/changePassword', [ViewController::class, 'changePassword']);
Route::get('/account', [ViewController::class, 'account']);
Route::get('/uploadedGames', [ViewController::class, 'uploadedGames']);
Route::get('/uploadGame', [ViewController::class, 'uploadGame']);
Route::get('/admin', [ViewController::class, 'admin']);
Route::get('/admin/users', [ViewController::class, 'users']);
Route::get('/admin/userGames/{id}', [ViewController::class, 'userGames']);
Route::post('/doRegister', [AuthController::class, 'doRegister']);
Route::post('/doVerify', [AuthController::class, 'doVerify']);
Route::post('/doLogin', [AuthController::class, 'doLogin']);
Route::post('/checkEmail', [AuthController::class, 'checkEmail']);
Route::post('/doChangePassword', [AuthController::class, 'doChangePassword']);
Route::get('/doLogout', [AuthController::class, 'doLogout']);
Route::get('/games/searchGames', [UserController::class, 'searchGames']);
Route::get('/uploadedGames/searchGames', [UserController::class, 'searchUserGames']);
Route::get('/deleteAccount', [UserController::class, 'deleteAccount']);
Route::post('/changePassword', [UserController::class, 'changePassword']);
Route::post('/doUploadGame', [UserController::class, 'doUploadGame']);
Route::get('/admin/searchGames', [AdminController::class, 'searchGames']);
Route::get('/admin/approveGame/{id}', [AdminController::class, 'approveGame']);
Route::get('/admin/rejectGame/{id}', [AdminController::class, 'rejectGame']);
Route::get('/admin/unblockUser/{id}', [AdminController::class, 'unblockUser']);
Route::get('/admin/blockUser/{id}', [AdminController::class, 'blockUser']);
Route::get('/admin/searchUsers', [AdminController::class, 'searchUsers']);
Route::get('/admin/userGames/{id}/searchGames', [AdminController::class, 'userGamesSearch']);
