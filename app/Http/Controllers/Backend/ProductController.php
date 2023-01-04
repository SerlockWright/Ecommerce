<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\MultiImg;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Image;

class ProductController extends Controller
{
    public function AllProduct(){
        $products = Product::latest()->get();
        return view('backend.product.product_all', compact('products'));
    }//End method

    public function AddProduct(){
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $activeVendor = User::where('status','active')->where('role','vendor')->latest()->get();
        return view('backend.product.product_add', compact('brands','categories','activeVendor'));
    }//End method

    public function StoreProduct(Request $request){
        $image = $request->file('product_thumbnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(800,800)->save('upload/products/thumbnail/'.$name_gen);
        $save_url = 'upload/products/thumbnail/'.$name_gen;

        $product_id = Product::insertGetId([
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'product_name' => $request->product_name,
            'product_tags' => $request->product_tags,
            'product_size' => $request->product_size,
            'product_color' => $request->product_color,
            'short_descp' => $request->short_descp,
            'long_descp' => $request->long_descp,
            'product_thumbnail' => $save_url,
            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'product_code' => $request->product_code,
            'product_qty' => $request->product_qty,
            'vendor_id' => $request->vendor_id,
            'hot_deals' => $request->hot_deals,
            'featured' => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals,
            'product_slug' => strtolower(str_replace(' ','-',$request->product_name)),
            'status' => 1,
            'created_at' => Carbon::now(),
        ]);
        //Multiple Image upload
        $images = $request->file('multi_img');
        foreach($images as $img){
            $image_gen = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
            Image::make($img)->resize(800,800)->save('upload/products/multi_image/'.$image_gen);
            $uploadPath = 'upload/products/multi_image/'.$image_gen;

            MultiImg::insert([
                'product_id' => $product_id,
                'photo_name' => $uploadPath,
                'created_at' => Carbon::now(),
            ]);
        }
        //End Multiple Image upload
        $notification = array(
            'message' => 'Product inserted successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.product')->with($notification);
    }//End method
}