<?php


use Illuminate\Foundation\Application;
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

Auth::routes();
//Route::get('/logout',function (){
//    \Illuminate\Support\Facades\Auth::logout();
Route::get('/', function () {

    return view('auth.login');
})->name('home');

//})->name('dashboard.logout');







