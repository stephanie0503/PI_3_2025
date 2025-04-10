<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // O nome da tabela no banco de dados
    protected $table = 'products';

    protected $fillable = [
        'product_code',
        'description',
        'category_id',
        'sumary',
        'image_url',
        'origin',
        'destination',
        'ipi',
        'icms',
        'suggested_retail_price',
    ];

}
