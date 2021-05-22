<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Parents
 *
 * @property        $id
 * @property        $user_id
 * @property        $amount
 * @property        $resource_id
 * @property        $created_at
 * @property        $updated_at
 * @package App\Models
 */
class TotalWorkers extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'total_workers';
    protected $fillable = [
        'user_id',
        'resource_id',
        'amount'
    ];
}
