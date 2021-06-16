<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Resource
 *
 * @property        $id
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

    public function automated(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ResourceAutomated::class)->wherePivot('user_id', auth()->user()->id);
    }

    public function enabled(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ResourceEnabled::class)->wherePivot('user_id', auth()->user()->id);
    }

    public function totalResources(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TotalResources::class)->wherePivot('user_id', auth()->user()->id);
    }

    public function isAutomated($userId): bool
    {
        return (ResourceAutomated::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && ResourceEnabled::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1);
    }

    public function isEnabled($userId): bool
    {
        return (ResourceEnabled::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && ResourceEnabled::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1);
    }

    public function canAutomate($userId): bool
    {
        $eligiblity = false;
        if(! ResourceAutomated::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && ResourceEnabled::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1) {
            $eligiblity = EligibleToAutomate::where(['resource_id' => $this->id, 'user_id' => $userId])
                                            ->exists() && EligibleToAutomate::where([
                    'resource_id' => $this->id,
                    'user_id'     => $userId
                ])->first()->status == 1;
        }

        return ($eligiblity);
    }

    public function canEnable($userId): bool
    {
        $eligiblity = false;
        if(! ResourceEnabled::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() || ResourceEnabled::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1) {
            $eligiblity = EligibleToEnable::where(['resource_id' => $this->id, 'user_id' => $userId])
                                          ->exists() && EligibleToEnable::where([
                    'resource_id' => $this->id,
                    'user_id'     => $userId
                ])->first()->status == 1;
        }

        return ($eligiblity);
    }

    public function canAddWorker($userId): bool
    {
        return (EligibleToAddWorker::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && EligibleToAddWorker::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1);
    }

    public function canAddTool($userId): bool
    {
        return (EligibleToAddTool::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && EligibleToAddTool::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1);
    }

    public function canAddForeman($userId): bool
    {
        return (EligibleToAddForeman::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && EligibleToAddForeman::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1);
    }

    public function amount($userId) : int
    {
        if(TotalResources::where(['resource_id' => $this->id, 'user_id' => $userId])->exists()) {
            return TotalResources::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->amount;
        }

        return 0;
    }
    public function resourceIncrementAmount($userId) : int
    {
        if(ResourceIncrementAmounts::where(['resource_id' => $this->id, 'user_id' => $userId])->exists()) {
            return ResourceIncrementAmounts::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->amount;
        }

        return 0;
    }

    public function totalWorkers($userId) : int
    {
        if(TotalWorkers::where(['resource_id' => $this->id, 'user_id' => $userId])->exists()) {
            return TotalWorkers::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->amount;
        }

        return 0;
    }

    public function totalTools($userId) : int
    {
        if(TotalTools::where(['resource_id' => $this->id, 'user_id' => $userId])->exists()) {
            return TotalTools::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->amount;
        }

        return 0;
    }

    public function totalForeman($userId) : int
    {
        if(TotalForeman::where(['resource_id' => $this->id, 'user_id' => $userId])->exists()) {
            return TotalForeman::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->amount;
        }

        return 0;
    }
}
