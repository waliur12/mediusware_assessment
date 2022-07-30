<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    public function product_variants()
    {
        
        return $this->belongsToMany(Variant::class,'product_variants','product_id','variant_id')->withPivot('variant');
    } 

    public function product_variant_price()
    {
        return $this->hasMany(ProductVariantPrice::class,'product_id');
    } 

    protected $dates = ['created_at'];

}
