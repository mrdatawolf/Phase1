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
class ResourceAutomated extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'resource_automated';
    protected $fillable = [
        'user_id',
        'resource_id',
        'status'
    ];

    public function resource(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
