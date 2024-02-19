<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'amount',
        'payment_method',
        'payment_status',
        'donator_names', 
        'donator_phone','currency'
    ];

}
