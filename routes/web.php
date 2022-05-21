<?php

use App\Models\Category;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('create/category', function () {
    // $created_category = Category::create([
    //     'title' => 'test 2 title',
    //     'slug' => 'test-2-title'
    // ]);
    // dd($created_category);

    // dd(Category::find(1));

    // dd(Category::where(['slug' => 'test-title'])->first());

    Category::find(1)->update([
        'title' => '7learn'
    ]);
});
