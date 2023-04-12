<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = [    
        'user_id',        
        'product_id',     
        'cart_id',        
        'quantity',        
        'price',          
        'payment_method',  
        'address',        
        'phone',          
        'city',           
        'pincode',         
        'status'   
    ];
}
       