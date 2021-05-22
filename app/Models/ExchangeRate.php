<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'exchange_rate';
    protected $fillable = [
        'user_id', 'resource_id', 'amount'
    ];
}
