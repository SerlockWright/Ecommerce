<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Image;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;
use Carbon\Carbon;

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

    public function VendorStoreProduct(Request $request) {
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
            'vendor_id' => Auth::user()->id,
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
            'message' => 'Vendor Product inserted successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('vendor.all.product')->with($notification);
    }//End
}