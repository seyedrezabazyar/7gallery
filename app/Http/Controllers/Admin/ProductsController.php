<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\StoreRequest;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function create()
    {
        return view('admin.products.add');
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();
    }
}
