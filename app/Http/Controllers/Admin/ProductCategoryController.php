<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategoryStoreRequest;
use App\Http\Requests\ProductCategoryUpdateRequest;
use App\Models\ProductCategory;
use Carbon\Carbon;
use Database\Factories\ProductCategoryFactory;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index(Request $request){
        //SQL Raw
        
        // $queryCount = DB::select('select count(*) as total from product_category');

        // $totalRecord = $queryCount[0]->total;
        // $itemPerPage = 10;
        // $totalPage = (int)ceil($totalRecord / $itemPerPage);
        // $page = $request->page ?? 1;
        // $current = ($page - 1) * $itemPerPage;

        // $productCategories = DB::select('SELECT * FROM product_category Limit ?,?', [$current, $itemPerPage]);

        // dd($totalPage);


        //QueryBuilder
        $productCategories = DB::table('product_category')->paginate(10);
        // dd($productCategories);

        //Eloquent
        // $productCategories = ProductCategory::all();
        // dd($productCategories);

        return view('admin.pages.product_category.index', ['productCategories' => $productCategories]);
    }

    public function create(){
        return view('admin.pages.product_category.create');
    }

    public function store(ProductCategoryStoreRequest $request){
        $name = $request->name;
        $slug = $request->slug;
        $status = $request->status;

        //SQL Raw
        // $check = DB::insert("INSERT INTO product_category (name, slug, status, created_at, updated_at) 
        // VALUES (?, ?, ?, ?, ?)", [$name, $slug, $status, Carbon::now(), Carbon::now()]);

        //Query Builder
        // $check = DB::table('product_category')->insert([
        //     'name' => $name,
        //     'slug' => $slug,
        //     'status' => $status,
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);

        //Eloquent ORM -> Object Relational Mapping
        $check = ProductCategory::create([
            'name' => $name,
            'slug' => $slug,
            'status' => $status
        ]);

        $message = $check ? "Tao danh muc thanh cong" : "Tao danh muc that bai";

        //Flash message
        return redirect()->route('admin.product_category.index')->with('msg', $message);
    }

    public function slug(Request $request){
        $name = $request->name;
        $slug = Str::slug($name);
        return response()->json(['slug' => $slug]);
    }

    public function destroy(string $id){
        //SQL Raw
        // $check = DB::delete("DELETE FROM product_category WHERE id = ?", [$id]);

        //Query Builder
        $check = DB::table('product_category')->where('id', $id)->delete();

        //Eloquent ORM -> Object Relational Mapping
        // $check = ProductCategory::destroy($id);

        $message = $check ? "Xoa danh muc thanh cong" : "Xoa danh muc that bai";

        //Flash message
        return redirect()->route('admin.product_category.index')->with('msg', $message);

    }

    public function detail(string $id){
        //SQL Raw
        // $productCategory = DB::select("SELECT * FROM product_category WHERE id =?", [$id]);

        // //Query Builder
        // $productCategory = DB::table('product_category')->where('id', '=', $id)->get();

        //Eloquent ORM -> Object Relational Mapping
        $productCategory = ProductCategory::find($id);

        return view('admin.pages.product_category.detail', ['productCategory' => $productCategory]);
    }

    public function update(ProductCategoryUpdateRequest $request, string $id){
        $name = $request->name;
        $slug = $request->slug;
        $status = $request->status;

        $productCategory = ProductCategory::find($id);
        $productCategory->name = $name;
        $productCategory->slug = $slug;
        $productCategory->status = $status;
        $productCategory->save();

        return redirect()->route('admin.product_category.index')->with('msg', "Cap nhat danh muc thanh cong");
    }
}
