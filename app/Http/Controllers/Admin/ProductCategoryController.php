<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index(){
        $name = 'LE VAN TEST 123123';
        $title = 'AAAAAA';
        //Pass variable to view

        //C1 : 
        // return view('admin.pages.product_category.index', [
        //     'name' => $name,
        //     'title' => $title
        // ]);

        //C2 :
        return view('admin.pages.product_category.index')
        ->with('name', $name)
        ->with('title', $title);

        //C3 :
        return view('admin.pages.product_category.index', compact('name', 'title'));
    }

    public function create(){
        return view('admin.pages.product_category.create');
    }

    public function store(Request $request){
        $name = $request->name;
        $status = $request->status;

        //key = input name
        //value = array = rules
        $request->validate([
            'name' => 'required|min:3|max:256',
            'status' => 'required|boolean'
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'name.min' => 'Tên danh mục phải có độ dài từ 3',
            'name.max' => 'Tên danh mục phải có độ dài 255 ký tự',
            'status.required' => 'Vui lòng chọn trạng thái'
        ]);

        dd($name, $status);
    }

    public function slug(Request $request){
        $name = $request->name;
        $slug = Str::slug($name);
        return response()->json(['slug' => $slug]);
    }
}
