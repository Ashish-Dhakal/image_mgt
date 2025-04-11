<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
