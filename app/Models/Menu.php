<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Genre;
use App\Models\Owner;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'owner_id', 'genre_id', 'price', 'image', 'quantity', 'product_code'];


    public function owners()
    {
        return $this->hasOne(Owner::class);
    }

    public function genres()
    {
        return $this->hasOne(Genre::class);
    }
}
