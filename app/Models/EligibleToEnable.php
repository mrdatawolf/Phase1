<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Parents
 *
 * @property        $id
 * @property        $user_id
 * @property        $resource_id
 * @property        $status
 * @property        $created_at
 * @property        $updated_at
 * @package App\Models
 */
class EligibleToEnable extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'eligible_to_enable';
    protected $fillable = [
        'user_id',
        'resource_id',
        'status'
    ];
}
