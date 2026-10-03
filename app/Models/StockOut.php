<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $table = 'stock_out';
    protected $fillable = [
        'reference_no',
        'product_id',
        'quantity',
        'note',
    ];
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
