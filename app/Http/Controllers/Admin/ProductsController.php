<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\StoreRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.add', compact('categories'));
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();
        $admin = User::where('email', 'admin@gmail.com')->first();

//        dd($validatedData['thumbnail_url']->getClientOriginalName());

        $createdProduct = Product::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
            'owner_id' => $admin->id
        ]);
        $path = 'products/' . $createdProduct->id . '/' . 'thumbnail_url_' . $validatedData['thumbnail_url']->getClientOriginalName();
        Storage::disk('public_storage')->put($path, File::get($validatedData['thumbnail_url']));
//        \ImageUploader::upload($validatedData['thumbnail_url']);
    }
}
