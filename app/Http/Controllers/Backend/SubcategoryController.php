<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;

class SubcategoryController extends Controller
{
    public function AllSubcategory(){
        $subcategories = Subcategory::latest()->get();
        return view('backend.subcategory.subcategory_all', compact('subcategories'));
    }//End method

    public function AddSubcategory(){
        $categories = Category::orderBy('category_name','ASC')->get();
        return view('backend.subcategory.subcategory_add', compact('categories'));
    }//End method

    public function StoreSubcategory(Request $request){
        Subcategory::insert([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ','-',$request->subcategory_name)),
        ]);
        $notification = array(
            'message' => 'Subcategory inserted successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.subcategory')->with($notification);
    }//End method

    public function EditSubcategory($id){
        $categories = Category::orderBy('category_name','ASC')->get();
        $subcategory = Subcategory::findOrFail($id);
        return view('backend.subcategory.subcategory_edit', compact('categories','subcategory'));
    }//End method

    public function UpdateSubcategory(Request $request) {
        $subcat_id = $request->id;
        Subcategory::findOrFail($subcat_id)->update([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-',$request->subcategory_name)),
        ]);
        $notification = array(
            'message' => 'Subcategory updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.subcategory')->with($notification);
    }//End method

    public function DeleteSubcategory($id){
        Subcategory::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Subcategory deleted successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.subcategory')->with($notification);
    }//End method
}