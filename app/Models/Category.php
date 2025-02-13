<?php

namespace App\Models;

use Illuminate\Support\Str;

class Category extends BaseModel
{
    public static $cacheKey = 'categories';

    protected $fillable = [
        'name',
    ];

    // Automatically generate the slug
    public static function boot()
    {
        parent::boot();
        static::creating(
            function ($category) {
                $category->slug = Str::slug($category->name);
            }
        );
    }

    public function rules($id = null)
    {
        $id = $id ?? request()->route('id');

        return [
            'name' => 'required|unique:categories,name',
        ];
    }
}
