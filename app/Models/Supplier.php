<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    // Specify the table name if it differs from the pluralized form of the model name
    protected $table = 'suppliers';

    // Allow mass assignment for these fields
    protected $fillable = [
        'name', 'contact_info'
    ];

    // Relationship to Product
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
