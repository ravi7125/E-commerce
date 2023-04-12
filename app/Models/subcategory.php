<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class subcategory extends Model
{
    use HasFactory;
    use softDeletes;
   
    protected $fillable = ['name', 'categories_id'];

    public function Category()
    {
        return $this->belongsToMany(Category::class, 'categories_id');
    }
}