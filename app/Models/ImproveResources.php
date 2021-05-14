<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Parents
 *
 * note: the resources required to improve a resource
 * @property        $id
 * @property        $resource_id
 * @property        $r1
 * @property        $r2
 * @property        $r3
 * @property        $r4
 * @property        $r5
 * @property        $r6
 * @property        $r7
 * @property        $r8
 * @property        $r9
 * @property        $r10
 * @property        $r11
 * @property        $r12
 * @property        $created_at
 * @property        $updated_at
 * @package App\Models
 */
class ImproveResources extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'improve_resources';
    protected $fillable = [
        'id',
        'resource_id',
        'r1',
        'r2',
        'r3',
        'r4',
        'r5',
        'r6',
        'r7',
        'r8',
        'r9',
        'r10',
        'r11',
        'r12'
    ];
}
