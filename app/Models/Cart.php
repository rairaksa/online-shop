<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    const STATUS_READY = "ready";
    const STATUS_ORDERED = "ordered";
    const STATUS_OUT_OF_STOCK = "out of stock";
}
