<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =[
        'id','product_name','product_code','category_id','decription','price','photo',
    ];
    public function category(){
        return $this->belongsTo('App\Category');
    }
}
