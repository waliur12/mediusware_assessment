<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{

    public function price_v_one()
    {
        return $this->belongsTo(ProductVariant::class,'product_variant_one','id');
    }
    public function price_v_two()
    {
        return $this->belongsTo(ProductVariant::class,'product_variant_two','id');
    }
    public function price_v_three()
    {
        return $this->belongsTo(ProductVariant::class,'product_variant_three','id');
    }
}
