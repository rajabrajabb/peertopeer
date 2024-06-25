<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoritServices extends Model
{
    use HasFactory;

    protected $table = 'favorite_services';

     protected $fillable = [
        'service_id',
        'user_id'
    ];


     public function service()
    {
        return $this->belongsTo(Service::class);
    }

}
