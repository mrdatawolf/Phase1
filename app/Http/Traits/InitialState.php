<?php

namespace App\Http\Traits;

use App\Models\AutomateResources;
use App\Models\EligibleToAddForeman;
use App\Models\EligibleToAddTool;
use App\Models\EligibleToAddWorker;
use App\Models\EligibleToAutomate;
use App\Models\EligibleToEnable;
use App\Models\EnableResources;
use App\Models\ResourceAutomated;
use App\Models\ResourceIncrementAmounts;
use App\Models\TotalForeman;
use App\Models\TotalResources;
use App\Models\TotalTools;
use App\Models\TotalWorkers;

trait InitialState
{
    public function isEligibleToAddForeman($id, $resourceId): bool
    {
        return (bool)EligibleToAddForeman::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->status;
    }


    public function isEligibleToAddTool($id, $resourceId): bool
    {
        return (bool)EligibleToAddTool::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->status;
    }


    public function isEligibleToAddWorker($id, $resourceId): bool
    {
        return (bool)EligibleToAddWorker::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->status;
    }


    public function isEligibleToAutomate($id, $resourceId): bool
    {
        return (bool)EligibleToAutomate::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->status;
    }


    public function isEligibleToEnable($id, $resourceId): bool
    {
        return (bool)EligibleToEnable::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->status;
    }


    public function isAutomated($id, $resourceId): bool
    {
        return (bool)ResourceAutomated::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->status;
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


    public function gatherTotalWorkers($id, $resourceId)
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


    public function setPlaceholderValues()
    {
        $this->placeholderNeeds = [
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


    public function getResourcesNeededToAutomate($resourceId)
    {
        $this->resourcesNeededToAutomate = [];
        $resourcesNeededToAutomate       = AutomateResources::where('resource_id', $resourceId)->first();
        for ($x = 1; $x <= 12; $x++) {
            $thisId = 'r'.$x;
            $amount = (int)$resourcesNeededToAutomate->$thisId;
            if ($amount > 0) {
                $this->resourcesNeededToAutomate[$x] = $amount;
            }
        }
    }


    public function getResourcesNeededToEnable($resourceId)
    {
        $this->resourcesNeededToEnable = [];
        $resourcesNeededToEnable       = EnableResources::where('resource_id', $resourceId)->first();
        for ($x = 1; $x <= 12; $x++) {
            $thisId = 'r'.$x;
            $amount = (int)$resourcesNeededToEnable->$thisId;
            if ($amount > 0) {
                $this->resourcesNeededToEnable[$x] = $amount;
            }
        }
    }

    public function getAutomationStatus($resourceId) {
        $ra = ResourceAutomated::where(['user_id' => auth()->id(), 'resource_id' => $resourceId])->first();
        $this->automated = $ra->status;
    }
}
