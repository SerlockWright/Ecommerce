<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;

class VendorProductController extends Controller
{
    public function VendorAllProduct(){
        $id = Auth::user()->id;
        $products = Product::where('vendor_id',$id)->latest()->get();
        return view('vendor.backend.product.vendor_product_all', compact('products'));
    }//End method

    public function VendorAddProduct(){
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        return view('vendor.backend.product.vendor_product_add', compact('brands','categories'));
    }//End

    public function VendorGetSubcategory($category_id){
        $vendorSubcategory = Subcategory::where('category_id',$category_id)->orderBy('subcategory_name', 'ASC')->get();
        return json_encode($vendorSubcategory);
    }//End
}