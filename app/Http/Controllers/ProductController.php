<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $products=Product::with('product_variants','product_variant_price.price_v_one','product_variant_price.price_v_two','product_variant_price.price_v_three')->paginate(3);
        // dd();
        // $data=DB::table('products')->join('product_variant_prices','products.id','product_variant_prices.product_id')->select('products.*','products.id as product_id','product_variant_prices.*')->get();
        
        return view('products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // dd($request->all());
    $product=[];
    $product_variant=[];
    $product['title']=$request->title;
    $product['sku']=$request->sku;
    $product['description']=$request->description;
    $create_product=Product::create($product);


    foreach($request->product_variant as $variant){
        
        foreach($variant['tags'] as $value){
            // dd($value);
            $product_variant['variant']=$value;
            $product_variant['variant_id']=$variant['option'];
            $product_variant['product_id']=$create_product->id;
            $create_variant=ProductVariant::create($product_variant);
        }

    }

    return response()->json([
        'status'=>true,
        'product'=>$create_product,
    ]);

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
