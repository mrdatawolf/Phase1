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

    public function isAutomated(): bool
    {
        $userId = auth()->user()->id;
        return (ResourceAutomated::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && ResourceEnabled::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1);
    }

    public function isEnabled(): bool
    {
        $userId = auth()->user()->id;

        return (ResourceEnabled::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && ResourceEnabled::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1);
    }

    public function canAutomate(): bool
    {
        $userId = auth()->user()->id;

        return (EligibleToAutomate::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && EligibleToAutomate::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1);
    }

    public function canEnable(): bool
    {
        $userId = auth()->user()->id;

        return (EligibleToEnable::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && EligibleToEnable::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1);
    }

    public function canAddWorker(): bool
    {
        $userId = auth()->user()->id;

        return (EligibleToAddWorker::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && EligibleToAddWorker::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1);
    }

    public function canAddTool(): bool
    {
        $userId = auth()->user()->id;

        return (EligibleToAddTool::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && EligibleToAddTool::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1);
    }

    public function canAddForeman(): bool
    {
        $userId = auth()->user()->id;

        return (EligibleToAddForeman::where(['resource_id' => $this->id, 'user_id' => $userId])->exists() && EligibleToAddForeman::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->status == 1);
    }

    public function amount() : int
    {
        $userId = auth()->user()->id;
        if(TotalResources::where(['resource_id' => $this->id, 'user_id' => $userId])->exists()) {
            return TotalResources::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->amount;
        }

        return 0;
    }
    public function resourceIncrementAmount() : int
    {
        $userId = auth()->user()->id;
        if(ResourceIncrementAmounts::where(['resource_id' => $this->id, 'user_id' => $userId])->exists()) {
            return ResourceIncrementAmounts::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->amount;
        }

        return 0;
    }

    public function totalWorkers() : int
    {
        $userId = auth()->user()->id;
        if(TotalWorkers::where(['resource_id' => $this->id, 'user_id' => $userId])->exists()) {
            return TotalWorkers::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->amount;
        }

        return 0;
    }

    public function totalTools() : int
    {
        $userId = auth()->user()->id;
        if(TotalTools::where(['resource_id' => $this->id, 'user_id' => $userId])->exists()) {
            return TotalTools::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->amount;
        }

        return 0;
    }

    public function totalForeman() : int
    {
        $userId = auth()->user()->id;
        if(TotalForeman::where(['resource_id' => $this->id, 'user_id' => $userId])->exists()) {
            return TotalForeman::where(['resource_id' => $this->id, 'user_id' => $userId])->first()->amount;
        }

        return 0;
    }
}
