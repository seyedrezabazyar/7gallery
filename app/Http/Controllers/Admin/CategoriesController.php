<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $createdCategory = Category::create([
            'title' => $request->title,
            'slug' => $request->slug
        ]);

        if (!$createdCategory) {
            return back()->with('failed', 'دسته بندی ایجاد نشد!');
        }
        return back()->with('success', 'دسته بندی ایجاد شد.');
    }
}
