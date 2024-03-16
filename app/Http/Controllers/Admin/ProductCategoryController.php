<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
