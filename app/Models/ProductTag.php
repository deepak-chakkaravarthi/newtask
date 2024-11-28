<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    // Set the table name if it's different from the default
    protected $table = 'product_tag';

    // Define the fillable fields
    protected $fillable = [
        'product_id', 'tag',
    ];

    // Define the relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
