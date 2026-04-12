<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['description', 'hsn_code', 'unit', 'default_rate', 'tax_rate'];
}
