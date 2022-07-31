<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Carbon;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $products=Product::with('product_variants','product_variant_price.price_v_one','product_variant_price.price_v_two','product_variant_price.price_v_three')->paginate(10);
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
       
    $product=[];
    $product['title']=$request->title;
    $product['sku']=$request->sku;
    $product['description']=$request->description;
    $create_product=Product::create($product);
    // dd($create_product);
    // $create_product=2;
    $first=[];
    $second=[];
    $third=[];

    foreach($request->product_variant as $key=>$variant){

        if($key==0){
            $f_data=$this->insertVariant($variant['tags'],$create_product->id,$variant['option']);
            $first= $f_data;
        }
        if($key==1){
            $f_data=$this->insertVariant($variant['tags'],$create_product->id,$variant['option']);
            $second= $f_data;
        } 
        if($key==2){
            $f_data=$this->insertVariant($variant['tags'],$create_product->id,$variant['option']);
            $third= $f_data;
        }     
        
        
        
    }

    // dd($second,$third);if
    
    if(!empty($second)&&!empty($third)){
        $colors = collect($first);
    
        $cross=$colors->crossJoin($second,$third);

    }
    elseif(!empty($second)){
        $colors = collect($first);
    
        $cross=$colors->crossJoin($second);
    }else{
        $cross=collect($first);
        
    }
            
           
            foreach($request->product_variant_prices as $k=>$variant_price){
                // dd($cross[$k]);
                ProductVariantPrice::create([
                    'product_variant_one'=>(empty($second)&&empty($third))?$cross[$k]: $cross[$k][0],
                    'product_variant_two'=>$cross[$k][1]??null,
                    'product_variant_three'=>$cross[$k][2]??null,
                    'price'=>$variant_price['price'],
                    'stock'=>$variant_price['stock'],
                    'product_id'=>$create_product->id,
                ]);

            }



    return response()->json([
        'status'=>true,
        'product'=>$create_product,
    ]);

    }

    private function insertVariant($array,$product_id,$variant_id){
        
        $array_data=[];
        $product_variant=[];
        foreach($array as $value){
            $product_variant['variant']=$value;
            $product_variant['variant_id']=$variant_id;
            $product_variant['product_id']=$product_id;
            $create_variant=ProductVariant::create($product_variant);
            $array_data[] = $create_variant->id;

        }
        return   $array_data;
       


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
    public function search(Request $request){
        // dd($request->all());
        $products=Product::with('product_variant_price')->whereDate('created_at', '=', Carbon::today()->toDateString())->get();
        // dd($products);
     
    $query = Product::query()->with('product_variant_price')
            ->where(function($query) use($request) {
            $query->where('title', 'Like', '%' . $request->title . '%')
            ->orWhereHas('product_variant_price', function ($query2) use($request) {
                // $query2->whereBetween('price', [$request->price_from, $request->to]);
                $query2->where('price', 100000);
            });
    });
    $data=$query->get();
    dd($data);



    }
}
