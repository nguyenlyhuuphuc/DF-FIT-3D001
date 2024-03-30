<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreateRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //SELECT product.*, product_category.name as 'product_category_name'
        // FROM `product`
        // INNER JOIN product_category on product.product_category_id = product_category.id;
        //Query Builder 
        // $products = DB::table('product')
        // ->select('product.*', 'product_category.name as product_category_name')
        // ->join('product_category', 'product.product_category_id', '=', 'product_category.id')
        // ->paginate(config('my_config.item_per_page'));

        $products = Product::with('productCategory')->withTrashed()->paginate(10);
        // $products = Product::paginate(config('my_config.item_per_page'));
        // dd($products);

        return view('admin.pages.product.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productCategories = ProductCategory::where('status', 1)->get();
        return view('admin.pages.product.create', ['productCategories' => $productCategories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->price = $request->price;
        $product->description = $request->description;
        $product->product_category_id = $request->product_category_id;
        $product->status = $request->status;

        if($request->hasFile('image')) {
            $fileName = $this->saveImageToSystem($request->file('image'));
            $product->image = $fileName;
        }
        $product->save();

        if($request->hasFile('image_gallery')){
            foreach($request->file('image_gallery') as $file){
                $fileName = $this->saveImageToSystem($file);

                $productImage = new ProductImage();
                $productImage->image = $fileName;
                $productImage->product_id = $product->id;
                $productImage->save();
            }
        }
    }

    private function saveImageToSystem(UploadedFile $file){
        $originName = $file->getClientOriginalName();

        $fileName = pathinfo($originName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = $fileName . '_' . uniqid() . '.' . $extension;

        $file->move(public_path('images'), $fileName);

        return $fileName;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.product.index')->with('msg', 'Xoa san pham thanh cong');
    }

    public function storeImage(Request $request){
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $fileName = $this->saveImageToSystem($file);

            $url = asset('images/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }

    public function restore(string $id){
        $product = Product::withTrashed()->find($id);
        $product->restore();
        return redirect()->route('admin.product.index')->with('msg', 'Khoi phuc san pham thanh cong');
    }
}
