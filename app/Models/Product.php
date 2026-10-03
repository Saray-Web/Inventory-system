<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'price',
        'quantity',
        'minimum_stock',
        'description',
        'image',
        'image_public_id',
        'is_active'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function stockIn(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }
    public function stockOut(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }
}
