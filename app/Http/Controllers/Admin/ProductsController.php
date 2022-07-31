<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\StoreRequest;
use App\Http\Requests\Admin\Products\UpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Utilities\ImageUploader;
use http\Env\Request;
use mysql_xdevapi\Exception;

class ProductsController extends Controller
{
    public function all()
    {
        $products = Product::paginate(10);
        return view('admin.products.all', compact('products'));
    }

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
    }

    public function edit($prodict_id)
    {
        $categories = Category::all();
        $product = Product::findOrFail($prodict_id);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateRequest $request, $product_id)
    {
        $validatedDate = $request->validated();
        $product = Product::findOrFail($product_id);
        $updatedProduct = $product->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
        ]);
    }

    public function delete($product_id)
    {
        $product = Product::findOrFail($product_id);
        $product->delete();
        return back()->with('success', 'محصول حذف شد.');
    }

    public function downloadDemo($product_id)
    {
        $product = Product::findOrFail($product_id);
        return response()->download(public_path($product->demo_url));
    }

    public function downloadSource($product_id)
    {
        $product = Product::findOrFail($product_id);
        return response()->download(storage_path('app/local_storage/' . $product->source_url));
    }

    privdate function uploadImages($createdProduct, $validatedData)
    {
        try {
            $basePath = 'products/' . $createdProduct->id . '/';
            $sourceImageFullPath = $basePath . 'source_url_' . $validatedData['source_url']->getClientOriginalName();

            $images = [
                'thumbnail_url' => $validatedData['thumbnail_url'],
                'demo_url' => $validatedData['demo_url']
            ];

            $imagesPath = ImageUploader::uploadMany($images, $basePath);
            ImageUploader::upload($validatedData['source_url'], $sourceImageFullPath, 'local_storage');

            $updatedProduct = $createdProduct->update([
                'thumbnail_url' => $imagesPath['thumbnail_url'],
                'demo_url' => $imagesPath['demo_url'],
                'source_url' => $sourceImageFullPath
            ]);

            if (!$updatedProduct) {
                throw new Exception('تصاویر آپلود نشدند!');
            }
            return back()->with('success', 'محصول ایجاد شد.');

        } catch (\Exception $e) {
            return back()->with('failed', $e->getMessage());
        }
    }
}
