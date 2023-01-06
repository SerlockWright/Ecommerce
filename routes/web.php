<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SubcategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Middleware\RedirectIfAuthenticated;

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
    return view('frontend.index');
});

//User Dashboard route
Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard',[UserController::class, 'UserDashboard'])->name('dashboard');
    //User Profile
    Route::post('/user/profile/store',[UserController::class, 'UserProfileStore'])->name('user.profile.store');
    Route::get('/user/logout',[UserController::class, 'UserLogout'])->name('user.logout');
    Route::post('/user/update/password',[UserController::class, 'UserUpdatePassword'])->name('user.update.password');
});

require __DIR__ . '/auth.php';

//Admin dashboard route
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');

    //Admin Profile
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    //Admin Change Password
    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('update.password');
});

//Vendor active/inactive route
Route::controller(AdminController::class)->group(function(){
    Route::get('/inactive/vendor', 'InactiveVendor')->name('inactive.vendor');   
    Route::get('/active/vendor', 'ActiveVendor')->name('active.vendor');
    Route::get('inactive/vendor/details/{id}', 'InactiveVendorDetails')->name('inactive.vendor.details');
    Route::post('/active/vendor/approve', 'ActiveVendorApprove')->name('active.vendor.approve');

    Route::get('active/vendor/details/{id}', 'ActiveVendorDetails')->name('active.vendor.details');
    Route::post('/inactive/vendor/approve', 'InactiveVendorApprove')->name('inactive.vendor.approve');
 });

//vendor dashboard route
Route::middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/vendor/dashboard', [VendorController::class, 'VendorDashboard'])->name('vendor.dashboard');
    Route::get('/vendor/logout', [VendorController::class, 'VendorDestroy'])->name('vendor.logout');


    //Vendor Profile
    Route::get('/vendor/profile', [VendorController::class, 'VendorProfile'])->name('vendor.profile');
    Route::post('/vendor/profile/store', [VendorController::class, 'VendorProfileStore'])->name('vendor.profile.store');
    //Vendor Change Password
    Route::get('/vendor/change/password', [VendorController::class, 'VendorChangePassword'])->name('vendor.change.password');
    Route::post('/vendor/update/password', [VendorController::class, 'VendorUpdatePassword'])->name('vendor.update.password');
});




//Brand Route
Route::middleware(['auth','role:admin'])->group(function(){
    Route::controller(BrandController::class)->group(function(){
        Route::get('/all/brand', 'AllBrand')->name('all.brand');
        //Creat Brand
        Route::get('/add/brand', 'AddBrand')->name('add.brand');
        Route::post('/store/brand', 'StoreBrand')->name('store.brand');
        //Update Brand
        Route::get('/edit/brand/{id}', 'EditBrand')->name('edit.brand');
        Route::post('/update/brand', 'UpdateBrand')->name('update.brand');
        //Delete Brand
        Route::get('/delete/brand/{id}', 'DeleteBrand')->name('delete.brand');

    });
});
//protect URL brand with role admin

//Category Route
Route::middleware(['auth','role:admin'])->group(function(){
    Route::controller(CategoryController::class)->group(function(){
        Route::get('/all/category', 'AllCategory')->name('all.category');
        //Creat Brand
        Route::get('/add/category', 'AddCategory')->name('add.category');
        Route::post('/store/category', 'StoreCategory')->name('store.category');
        //Update Brand
        Route::get('/edit/category/{id}', 'EditCategory')->name('edit.category');
        Route::post('/update/category', 'UpdateCategory')->name('update.category');
        //Delete Brand
        Route::get('/delete/category/{id}', 'DeleteCategory')->name('delete.category');
    });
});

//Subcategory Route
Route::middleware(['auth','role:admin'])->group(function(){
    Route::controller(SubcategoryController::class)->group(function(){
        Route::get('/all/subcategory', 'AllSubcategory')->name('all.subcategory');
        //Creat Brand
        Route::get('/add/subcategory', 'AddSubcategory')->name('add.subcategory');
        Route::post('/store/subcategory', 'StoreSubcategory')->name('store.subcategory');
        //Update Brand
        Route::get('/edit/subcategory/{id}', 'EditSubcategory')->name('edit.subcategory');
        Route::post('/update/subcategory', 'UpdateSubcategory')->name('update.subcategory');
        //Delete Brand
        Route::get('/delete/subcategory/{id}', 'DeleteSubcategory')->name('delete.subcategory');
        //Get Subcategory
        Route::get('/subcategory/ajax/{category_id}' , 'GetSubcategory');    
    });
});

Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->middleware(RedirectIfAuthenticated::class);

//Register Vendor Route
Route::get('/vendor/login', [VendorController::class, 'VendorLogin'])->name('vendor.login')->middleware(RedirectIfAuthenticated::class);
Route::get('/become/vendor', [VendorController::class, 'BecomeVendor'])->name('become.vendor');
Route::post('/vendor/register', [VendorController::class, 'VendorRegister'])->name('vendor.register');

//Product Route
Route::middleware(['auth','role:admin'])->group(function(){
    Route::controller(ProductController::class)->group(function(){
        Route::get('/all/product', 'AllProduct')->name('all.product');
        //Add Product
        Route::get('/add/product', 'AddProduct')->name('add.product');
        Route::post('/store/product', 'StoreProduct')->name('store.product');
        //Edit Product
        Route::get('/edit/product/{id}', 'EditProduct')->name('edit.product');
        Route::post('/update/product', 'UpdateProduct')->name('update.product');
        //Update Product thumbnail
        Route::post('/update/product/thumbnail', 'UpdateProductThumbnail')->name('update.product.thumbnail');
        //Update Product Multi Image
        Route::post('/update/product/multi/image', 'UpdateProductMultiImage')->name('update.product.multi.image');
        //Delete Product Multi Image
        Route::get('/delete/product/multi/image/{id}', 'DeleteProductMultiImage')->name('delete.product.multi.image');
        //Inactive product
        Route::get('inactive/product/{id}', 'ProductInactive')->name('product.inactive');
        //Active product
        Route::get('active/product/{id}', 'ProductActive')->name('product.active');
        //Delete product
        Route::get('delete/product/{id}', 'DeleteProduct')->name('delete.product');
    });
});