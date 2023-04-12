<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Products extends Model 
{
    use HasFactory;
    use softDeletes;
   
    protected $fillable = ['name', 'categories_id', 'subcategories_id','description','price','quantity'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategories_id');
    }
}