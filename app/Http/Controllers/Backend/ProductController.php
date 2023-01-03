<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\MultiImg;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function AllProduct(){
        $products = Product::latest()->get();
        return view('backend.product.product_all', compact('products'));
    }//End method

    public function AddProduct(){
        return view('backend.product.product_add');
    }//End method
}