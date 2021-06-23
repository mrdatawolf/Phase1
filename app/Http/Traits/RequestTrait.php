<?php namespace App\Http\Traits;

use App\Models\ResourceAutomated;
use App\Models\ResourceEnabled;
use App\Models\ResourceIncrementAmounts;
use App\Models\TotalResources;

/**
 * Trait RequestTrait
 * note: requires UpdateResourceTotal trail to use some of these methods.
 * note: also status trait is required.
 *
 * @package App\Http\Traits
 */
trait RequestTrait
{
    /**
     * note: add the current resourceIncrementAmount for a resource to it's total and check if that allows other
     * resources to be enabled
     *
     * @param int $resourceId
     * @param int $multiplier
     *
     * @return float|int
     */
    public function requestGather(int $resourceId, int $multiplier = 1)
    {
        $userId = auth()->id();
        $enabled = (ResourceEnabled::where(['user_id' => $userId, 'resource_id' => $resourceId])->first()->status == 1);
        $gatherAmount = ResourceIncrementAmounts::where(['user_id' => $userId, 'resource_id' => $resourceId])->first()->amount;
        $return = 0;
        if ($enabled) {
            $tr         = TotalResources::where(['user_id' => $userId, 'resource_id' => $resourceId])->first();
            $tr->amount += $gatherAmount * $multiplier;
            $tr->save();
            $return = $tr->amount;
        }

        return $return;
    }


    /**
     * @param $resourceId
     *
     * @return bool
     */
    public function requestEnable($resourceId): bool
    {
        $return = false;
        $userId = auth()->id();
        $enabled = (ResourceEnabled::where(['user_id' => $userId, 'resource_id' => $resourceId])->first()->status == 1);
        $this->updateStatus($resourceId);
        if ($enabled) {
            $this->payForAddition($resourceId, 'enable');
            $re         = ResourceEnabled::where(['user_id' => auth()->id(), 'resource_id' => $resourceId])->first();
            $re->status = true;
            $re->save();
            $this->enabled[$resourceId] = $re->status;
            $resourceTypeAmount         = $this->updateResourceTypeTotal($resourceId, 'worker');
            $resourcesNeededToAddType   = $this->getResourcesRequiredToAddWorker($resourceId);
            if ( ! empty($resourceTypeAmount) && ( ! empty($resourcesNeededToAddType) || $resourcesNeededToAddType === 0)) {
                $this->emit('updateTotal', $resourceId, 'worker', $resourceTypeAmount, $resourcesNeededToAddType);
            }
            $this->updateResourceGatherAmount($resourceId);
            $resourcesNeededToAddType = $this->getResourcesRequiredToAddWorker($resourceId);
            if ( ! empty($resourceTypeAmount) && ( ! empty($resourcesNeededToAddType) || $resourcesNeededToAddType === 0)) {
                $this->emit('updateTotal', $resourceId, 'worker', $resourceTypeAmount, $resourcesNeededToAddType);
            }
            $this->updateResourceGatherAmount($resourceId);
            $this->setStatus('enable', $resourceId);
            $this->updateCurrentResourceTotals();
            $return = true;
        }

        return $return;
    }


    /**
     * @param $id
     *
     * @return bool
     */
    public function requestAutomate($id): bool
    {
        $return = false;
        $this->updateStatus($id);
        if ($this->eligibleToAutomate[$id]) {
            $ra         = ResourceAutomated::where(['user_id' => auth()->id(), 'resource_id' => $id])->first();
            $ra->status = true;
            $ra->save();
            $this->automated[$id] = $ra->status;
            $this->payForAddition($id, 'automate');
            $this->setStatus('automated', $id);
            $this->updateCurrentResourceTotals();
            $return = true;
        }

        return $return;
    }


    /**
     * @param $resourceId
     * @param $type
     *
     * @return int
     */
    public function requestAdd($resourceId, $type): int
    {
        $resourceTypeAmount = 0;
        $this->updateStatus($resourceId);
        if ($this->isEligible($resourceId, $type)) {
            $this->payForAddition($resourceId, $type);
            $this->updateResourcesNeeded($resourceId, $type);
            $resourceTypeAmount = $this->updateResourceTypeTotal($resourceId, $type);
            switch ($type) {
                case 'worker':
                    $resourcesNeededToAddType = $this->getResourcesRequiredToAddWorker($resourceId);
                    break;
                case 'tool':
                    $resourcesNeededToAddType = $this->getResourcesRequiredToAddTool($resourceId);
                    break;
                case 'foreman':
                    $resourcesNeededToAddType = $this->getResourcesRequiredToAddForeman($resourceId);
                    break;
            }
            if ( ! empty($resourceTypeAmount) && ( ! empty($resourcesNeededToAddType) || (isset($resourcesNeededToAddType) && $resourcesNeededToAddType === 0))) {
                $this->emit('updateTotal', $resourceId, $type, $resourceTypeAmount, $resourcesNeededToAddType);
            }
            $this->updateResourceGatherAmount($resourceId);
            $this->updateAllStatus();
            $this->updateCurrentResourceTotals();
        }

        return $resourceTypeAmount;
    }

    private function updateStatus($resourceId) {
        $this->setStatus('canBeEnabled', $resourceId);
        $this->setStatus('canBeAutomated', $resourceId);
        $this->setStatus('worker', $resourceId);
        $this->setStatus('tool', $resourceId);
        $this->setStatus('foreman', $resourceId);
    }


    /*
     * verify validity of actions
     */

    /**
     * @param int    $id
     * @param string $type
     *
     * @return bool
     */
    private function isEligible(int $id, string $type): bool
    {
        switch ($type) {
            case 'worker' :
                return $this->eligibleToAddWorker[$id];
            case 'tool' :
                return $this->eligibleToAddTool[$id];
            case 'foreman' :
                return $this->resourcesNeededToAddForeman[$id];
            default :
                return false;
        }
    }
}
