<?php namespace App\Objects;

use App\Http\Traits\Status;
use App\Models\AutomateResources;
use App\Models\EligibleToAutomate;
use App\Models\ResourceAutomated;
use App\Models\ResourceEnabled;
use App\Models\TotalResources;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Automate
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
        if(is_bool($status)) {
            $this->status = $status;
        } else {
            $this->status = ($status == 1);
        }
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
        $this->eligibleToActivate = $eligibleToActivate;
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
        $resourcesNeeded = AutomateResources::where('resource_id', $resourceId)->first();
        for ($x = 1; $x <= Resource::all()->count(); $x++) {
            $thisId = 'r'.$x;
            $amount = (int)$resourcesNeeded->$thisId;
            if ($amount > 0) {
                $required[$x] = $amount;
            }
        }

        return $required;
    }

    private function gatherStatus(): bool
    {
        $enabled = ResourceEnabled::where([
            'user_id'     => $this->owner,
            'resource_id' => $this->resourceId
        ])->first()->status == 1;
        if($enabled) {

            return (ResourceAutomated::where([
                'user_id'     => $this->owner,
                'resource_id' => $this->resourceId
            ])->first()->status == 1);
        }

        return false;
    }


    private function gatherEligiblity(): bool
    {
        if(! $this->status) {
            $enabled = ResourceEnabled::where([
                    'user_id'     => $this->owner,
                    'resource_id' => $this->resourceId
                ])->first()->status == 1;
            if ($enabled) {
                return (EligibleToAutomate::where([
                            'user_id'     => $this->owner,
                            'resource_id' => $this->resourceId
                        ])->first()->status == 1);
            }
        }

        return false;
    }


    /**
     * @return bool
     */
    public function getStatus(): bool
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
            $ra         = ResourceAutomated::where(['user_id' => $this->owner, 'resource_id' => $this->resourceId])
                                           ->first();
            $ra->status = 1;
            $ra->save();
            $this->updateAllStatus();

            return true;
        }

        return false;
    }


    public function deactivate(): bool
    {
        $re         = ResourceAutomated::where(['user_id' => $this->owner, 'resource_id' => $this->resourceId])->first();
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
