<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_name',
        'image_path',
        'image_alitext',
        'image_category_id'
    ];

    public function category()
    {
        return $this->belongsTo(ImageCategory::class, 'image_category_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
