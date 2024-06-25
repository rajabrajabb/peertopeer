<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

  protected $fillable = [
        'name',
        'type_id',
        'category_id',
        'image',
        'description',
        'location',
        'user_id',
        'search_value',
        'service_type',
        'price'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function type(){
        return $this->belongsTo(Type::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
