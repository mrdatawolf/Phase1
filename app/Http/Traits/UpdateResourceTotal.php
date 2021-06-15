<?php

namespace App\Http\Traits;

use App\Models\ResourceIncrementAmounts;
use App\Models\TotalForeman;
use App\Models\TotalTools;
use App\Models\TotalWorkers;

trait UpdateResourceTotal
{
    private function updateResourceTypeTotal($resourceId, $type)
    {
        $resourceTypeAmount = 0;
        switch ($type) {
            case 'worker' :
                $tw = TotalWorkers::where(['user_id' => auth()->id(), 'resource_id' => $resourceId])->first();
                $tw->amount++;
                $tw->save();
                $this->resourceWorkers[$resourceId] = $tw->amount;
                $resourceTypeAmount                 = $this->resourceWorkers[$resourceId];
                break;
            case 'tool' :
                $tt = TotalTools::where(['user_id' => auth()->id(), 'resource_id' => $resourceId])->first();
                $tt->amount++;
                $tt->save();
                $this->resourceTools[$resourceId] = $tt->amount;
                $resourceTypeAmount               = $this->resourceTools[$resourceId];

                break;
            case 'foreman' :
                $tf = TotalForeman::where(['user_id' => auth()->id(), 'resource_id' => $resourceId])->first();
                $tf->amount++;
                $tf->save();
                $this->resourceForemen[$resourceId] = $tf->amount;
                $resourceTypeAmount                 = $this->resourceForemen[$resourceId];

                break;
        }

        return $resourceTypeAmount;

    }


    private function updateResourceGatherAmount($id)
    {
        $toolBoost                       = $this->getToolBoost($id);
        $foremenBoost                    = $this->getForemanBoost($id);
        $this->resourceGatherAmount[$id] = (int)round($this->resourceWorkers[$id] * $foremenBoost * $toolBoost, 0);
        $ria                             = ResourceIncrementAmounts::where(['user_id'     => auth()->id(),
                                                                            'resource_id' => $id
        ])->first();
        $ria->amount                     = $this->resourceGatherAmount[$id];
        $ria->save();
        $this->emit('updateTotal', $id, 'gather', $this->resourceGatherAmount[$id], 0);
    }


    private function getToolBoost($id)
    {
        $maximumToolsPerWorker = 2;
        $toolBoost             = 1;
        if ($this->resourceTools[$id] > 0 && $this->resourceWorkers[$id] > 0) {
            $toolsUsage = $this->resourceTools[$id] / $this->resourceWorkers[$id];
            $toolBoost  += ($toolsUsage < $maximumToolsPerWorker) ? $toolsUsage : $maximumToolsPerWorker;
        }

        return $toolBoost;
    }

    private function getForemanBoost($id)
    {
        $maxWorkersPerForeman = 5;
        $foremenBoost         = 1;
        $foremen              = $this->resourceForemen[$id];
        if ($foremen >= 1 && $this->resourceWorkers[$id] >= 1) {
            $foremenNeeded = (int)round($this->resourceWorkers[$id] / $maxWorkersPerForeman, 0);
            if ($foremen > $foremenNeeded * 2) {
                $foremenBoost = 1;
            } elseif ($foremen > $foremenNeeded) {
                $foremenBoost += $foremenNeeded - ($foremen / $foremenNeeded) / 2;
            } elseif ($foremen === $foremenNeeded) {
                $foremenBoost = $foremenNeeded;
            } else {
                $foremenBoost += $foremen / $foremenNeeded;
            }
        }

        return $foremenBoost;
    }
}
