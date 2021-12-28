<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function scopeSearch(Builder $query, $term): Builder
    {
        if($term !== '' || $term !== null) {
            return $query->where('name', 'like', "%$term%");
        }
    }

    public function r_user(){
        return $this->belongsToMany(User::class, 'product_user', 'product_id', 'user_id' );
    }
}
