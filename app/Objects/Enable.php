<?php namespace App\Objects;

use App\Http\Traits\Status;
use App\Models\EligibleToEnable;
use App\Models\EnableResources;
use App\Models\Resource;
use App\Models\ResourceEnabled;
use App\Models\TotalResources;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enable
{
    use HasFactory;

    private $cost = [];
    private $owner;
    private $resourceId;
    private $eligibleToActivate;
    private $status;

    use Status;

    public function __construct($resourceId)
    {
        $this->setResourceId($resourceId);
        $this->setOwner(auth()->id());
        $currentCost = $this->gatherCost($resourceId);
        $this->setCost($currentCost);
        $status = $this->gatherStatus();
        $this->setStatus($status);
        $eligible = $this->gatherEligiblity();
        $this->setEligibleToActivate($eligible);
    }


    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }


    /**
     * @param mixed $resourceId
     */
    public function setResourceId($resourceId): void
    {
        $this->resourceId = $resourceId;
    }


    /**
     * @param mixed $eligibleToActivate
     */
    public function setEligibleToActivate($eligibleToActivate): void
    {
        if ( ! $this->status) {
            $this->eligibleToActivate = $eligibleToActivate;
        }
    }


    /**
     * @param mixed $owner
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }


    /**
     * @param mixed $cost
     */
    public function setCost($cost): void
    {
        $this->cost = $cost;
    }


    /**
     * @param $resourceId
     *
     * @return array
     */
    private function gatherCost($resourceId): array
    {
        $required        = [];
        $resourcesNeeded = EnableResources::where('resource_id', $resourceId)->first();
        for ($x = 1; $x <= Resource::all()->count(); $x++) {
            $thisId = 'r'.$x;
            $amount = (int)$resourcesNeeded->$thisId;
            if ($amount > 0) {
                $required[$x] = $amount;
            }
        }

        return $required;
    }


    /**
     * @return bool
     */
    private function gatherStatus(): bool
    {
        return (ResourceEnabled::where([
                'user_id'     => $this->owner,
                'resource_id' => $this->resourceId
            ])->first()->status == 1);
    }


    /**
     * @return bool
     */
    private function gatherEligiblity(): bool
    {
        if ( ! $this->status) {
            return (EligibleToEnable::where(['user_id' => $this->owner, 'resource_id' => $this->resourceId])
                                    ->first()->status == 1);
        }

        return false;
    }


    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }


    /**
     * @return mixed
     */
    public function getEligibleToActivate()
    {
        return $this->eligibleToActivate;
    }


    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }


    /**
     * @return array
     */
    public function getCost(): array
    {
        return $this->cost;
    }


    public function activate(): bool
    {
        if ($this->eligibleToActivate) {
            $this->payForActivation();
            $re         = ResourceEnabled::where(['user_id' => $this->owner, 'resource_id' => $this->resourceId])
                                         ->first();
            $re->status = 1;
            $re->save();
            $this->updateAllStatus();

            return true;
        }

        return false;
    }


    public function deactivate(): bool
    {
        $re         = ResourceEnabled::where(['user_id' => $this->owner, 'resource_id' => $this->resourceId])->first();
        $re->status = 0;
        $re->save();
        $this->updateAllStatus();

        return true;
    }


    private function payForActivation()
    {
        foreach ($this->cost as $resourceId => $cost) {
            $tr = TotalResources::where(['user_id' => $this->owner, 'resource_id' => $resourceId])->first();
            if ($cost > 0) {
                $tr->amount -= $cost;
                $tr->save();
            }
        }
    }
}
