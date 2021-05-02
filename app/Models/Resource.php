<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Parents
 *
 * @property        $user_id
 * @property        $name
 * @property        $created_at
 * @property        $updated_at
 * @package App\Models
 */
class Resource extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'resources';
    protected $fillable = [
        'name'
    ];
}
