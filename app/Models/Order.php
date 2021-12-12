<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_WAITING_PAYMENT = "waiting_payment";
    const STATUS_PAID = "paid";
    const STATUS_EXPIRED = "expire";
}
