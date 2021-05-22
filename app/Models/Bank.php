<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'bank';
    protected $fillable = [
        'user_id', 'amount'
    ];
}
