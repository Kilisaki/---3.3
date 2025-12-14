<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category',
        'brand',
        'attributes',
        'image',
        'is_featured'
    ];

    protected $casts = [
        'attributes' => 'array',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
    ];

    // Мутаторы
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => number_format($value, 2, '.', ' '),
            set: fn ($value) => str_replace([' ', ','], ['', '.'], $value)
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value)
        );
    }

    // Отношения
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getMainImageAttribute()
    {
        // Проверяем, загружены ли изображения
        if (!$this->relationLoaded('images')) {
            $this->load('images');
        }
        
        return $this->images->where('is_main', true)->first() 
            ?? $this->images->first() 
            ?? null;
    }
}