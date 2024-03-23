<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Carbon\Carbon;
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

    public function store(Request $request){
        //key = input name
        //value = array = rules
        $request->validate([
            'name' => 'required|min:3|max:256',
            'slug' => 'required|min:3|max:256',
            'status' => 'required|boolean'
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'name.min' => 'Tên danh mục phải có độ dài từ 3',
            'name.max' => 'Tên danh mục phải có độ dài 255 ký tự',
            'slug.required' => 'Vui lòng nhập tên :attribute',
            'slug.min' => 'Tên :attribute phải có độ dài từ 3',
            'slug.max' => 'Tên :attribute phải có độ dài 255 ký tự',
            'status.required' => 'Vui lòng chọn trạng thái'
        ]);

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
}
