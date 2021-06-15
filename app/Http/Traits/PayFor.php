<?php namespace App\Http\Traits;

use App\Models\AutomateResources;
use App\Models\EnableResources;
use App\Models\ResourceIncrementAmounts;
use App\Models\TotalForeman;
use App\Models\TotalResources;
use App\Models\TotalTools;
use App\Models\TotalWorkers;

/**
 * Trait PayFor
 * note: some of this is duplicated in the ResourcesRequired trait.  We should find a proper way to seperate them out of these traits.
 *
 * @package App\Http\Traits
 */
trait PayFor
{
    public $placeholderNeeds= [
        1  => 5,
        2  => 10,
        3  => 7,
        4  => 20,
        5  => 12,
        6  => 40,
        7  => 60,
        8  => 22,
        9  => 120,
        10 => 200,
        11 => 5,
        12 => 300
    ];

    private function payForAddition($resourceId, $type)
    {
        $tr = TotalResources::where(['user_id' => auth()->id(), 'resource_id' => $resourceId])->first();
        switch ($type) {
            case 'worker' :
                $resourcesNeededToAddType   = $this->getResourcesRequiredToAddWorker($resourceId);
                $tr->amount -= $resourcesNeededToAddType;
                $tr->save();
                break;
            case 'tool' :
                $resourcesNeededToAddType   = $this->getResourcesRequiredToAddTool($resourceId);
                $tr->amount -= $resourcesNeededToAddType;
                $tr->save();
                break;
            case 'foreman' :
                $resourcesNeededToAddType   = $this->getResourcesRequiredToAddForeman($resourceId);
                $tr->amount -= $resourcesNeededToAddType;
                $tr->save();
                break;
            case 'automate' :
                foreach ($this->resourcesNeededToAutomate[$resourceId] as $rId => $rAmount) {
                    $tr         = TotalResources::where(['user_id' => auth()->id(), 'resource_id' => $rId])->first();
                    $tr->amount -= $rAmount;
                    $tr->save();
                }
                break;
            case 'enable' :
                foreach ($this->resourcesNeededToEnable[$resourceId] as $rId => $rAmount) {
                    $tr         = TotalResources::where(['user_id' => auth()->id(), 'resource_id' => $rId])->first();
                    $tr->amount -= $rAmount;
                    $tr->save();
                }
                break;
        }
    }

    public function getResourcesRequiredToAddWorker($resourceId)
    {
        return $this->placeholderNeeds[$resourceId] * $this->gatherTotalWorkers(auth()->id(), $resourceId);
    }


    public function getResourcesRequiredToAddTool($resourceId)
    {
        return $this->placeholderNeeds[$resourceId] * $this->gatherTotalTools(auth()->id(), $resourceId);
    }


    public function getResourcesRequiredToAddForeman($resourceId)
    {
        return $this->placeholderNeeds[$resourceId] * $this->gatherTotalForemen(auth()->id(), $resourceId);
    }


    public function getResourcesNeededToAutomate($resourceId): array
    {
        $required = [];
        $resourcesNeeded     = AutomateResources::where('resource_id', $resourceId)->first();
        for ($x = 1; $x <= 12; $x++) {
            $thisId = 'r'.$x;
            $amount = (int) $resourcesNeeded->$thisId;
            if ($amount > 0) {
                $required[$x] = $amount;
            }
        }

        return $required;
    }


    public function getResourcesNeededToEnable($resourceId): array
    {
        $required = [];
        $resourcesNeeded       = EnableResources::where('resource_id', $resourceId)->first();
        for ($x = 1; $x <= 12; $x++) {
            $thisId = 'r'.$x;
            $amount = (int) $resourcesNeeded->$thisId;
            if ($amount > 0) {
                $required[$x] = $amount;
            }
        }

        return $required;
    }

    public function gatherTotalResource($id, $resourceId): int
    {
        return (int)TotalResources::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->amount;
    }


    public function gatherTotalForemen($id, $resourceId): int
    {
        return (int)TotalForeman::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->amount;
    }


    public function gatherTotalTools($id, $resourceId): int
    {
        return (int)TotalTools::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->amount;
    }


    public function gatherTotalWorkers($id, $resourceId): int
    {
        return (int)TotalWorkers::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->amount;
    }


    public function gatherResourceIncrementAmount($id, $resourceId): int
    {
        return (int)ResourceIncrementAmounts::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->amount;
    }
}
