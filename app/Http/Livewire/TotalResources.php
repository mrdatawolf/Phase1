<?php namespace App\Http\Livewire;

/**
 * purpose:
 * 1. hold the total stored amount for each resource.
 * 2. add to the amount of a resource when it's added.
 * 3. define if a given resource is available.
 * 4. set the minimum resources needed to make a resource available.
 */

use App\Http\Traits\InitialState;
use App\Models\ImproveMultiplier;
use App\Models\Resource;
use App\Models\ResourceAutomated;
use App\Models\ResourceEnabled;
use App\Models\ResourceIncrementAmounts;
use App\Models\TotalForeman;
use App\Models\TotalTools;
use App\Models\TotalWorkers;
use Livewire\Component;

class TotalResources extends Component
{
    use InitialState;

    public $totals;
    /**
     * @var false[]|mixed
     * note: this tells us if the user has gathered enough resources to allow resource(s) to be enabled
     */
    public $eligibleToEnable;

    /**
     * note: this tells us if the user has gathered enough resources to allow resource(s) to be automated
     *
     * @var false[]|mixed
     */
    public $eligibleToAutomate;

    /**
     * note: this tells us if the user has gathered enough resources to allow another worker
     *
     * @var false[]|mixed
     */
    public $eligibleToAddWorker;

    /**
     * note: this tells us if the user has gathered enough resources to allow another tool to be added
     *
     * @var false[]|mixed
     */
    public $eligibleToAddTool;

    /**
     * note: this tells us if the user has gathered enough resources to allow another foreman to be added
     *
     * @var false[]|mixed
     */
    public $eligibleToAddForeman;

    /**
     * note: resources needed to enable gathering of a resource
     * note2: resource id to enable [ resource needed =>amount of the resouce needed ]
     */
    public $resourcesNeededToEnable;

    /**
     * note: resources needed to automate gathering of a resource
     * note2: resource id to automate [ resource needed => amount of the resouce needed ]
     */
    public $resourcesNeededToAutomate;

    /**
     * note: resources needed to improve gathering of a resource
     * note2: resource id to improve [ resource needed => amount of the resource needed ]
     */
    public $resourcesNeededToAddWorker;

    /**
     * note: resources needed to improve gathering of a resource
     * note2: resource id to improve [ resource needed => amount of the resource needed ]
     */
    public $resourcesNeededToAddTool;

    /**
     * note: resources needed to improve gathering of a resource
     * note2: resource id to improve [ resource needed => amount of the resource needed ]
     */
    public $resourcesNeededToAddForeman;

    /**
     * note: resources needed to improve gathering of a resource
     * note2: resource id to improve [ resource needed => amount of the resource needed ]
     */
    public $improveMultiplier;

    /**
     * note: base amount of resources to increment by
     */
    public $resourceGatherAmount;

    /**
     * note: base workers of resources
     */
    public $resourceWorkers;

    /**
     * note: base tools for resources
     */
    public $resourceTools;

    /**
     * note: base amount of foremen for resources
     */
    public $resourceForemen;

    public $enabled;
    public $automated;
    public $resources;
    public $placeholderNeeds;

    public $listeners = [
        'requestGather',
        'requestEnable',
        'requestAutomate',
        'requestAdd',
        'checkAutomated',
        'checkDebt',
        'bankDeposit'
    ];


    public function mount()
    {
        $this->resources = Resource::get();
        $this->setPlaceholderValues();
        foreach ($this->resources as $resource) {
            $this->totals[$resource->id]               = $this->gatherTotalResource(auth()->id(), $resource->id);
            $this->resourceWorkers[$resource->id]      = $this->gatherTotalWorkers(auth()->id(), $resource->id);
            $this->resourceTools[$resource->id]        = $this->gatherTotalTools(auth()->id(), $resource->id);
            $this->resourceForemen[$resource->id]      = $this->gatherTotalForemen(auth()->id(), $resource->id);
            $this->resourceGatherAmount[$resource->id] = $this->gatherResourceIncrementAmount(auth()->id(),
                $resource->id);
            $this->eligibleToEnable[$resource->id]     = $this->isEligibleToEnable(auth()->id(), $resource->id);
            $this->eligibleToAutomate[$resource->id]   = $this->isEligibleToAutomate(auth()->id(), $resource->id);
            $this->eligibleToAddWorker[$resource->id]  = $this->isEligibleToAddWorker(auth()->id(), $resource->id);
            $this->eligibleToAddTool[$resource->id]    = $this->isEligibleToAddTool(auth()->id(), $resource->id);
            $this->eligibleToAddForeman[$resource->id] = $this->isEligibleToAddForeman(auth()->id(), $resource->id);
            $this->automated[$resource->id]            = $this->isAutomated(auth()->id(), $resource->id);
            $this->improveMultiplier[$resource->id]    = $this->getImproveMultiplier($resource->id);
            $this->gatherEnableStatus($resource->id);
            $this->getResourcesNeededToAutomate($resource->id);
            $this->getResourcesNeededToEnable($resource->id);

            $this->resourcesNeededToAddWorker[$resource->id]  = $this->getResourcesRequiredToAddWorker($resource->id);
            $this->resourcesNeededToAddTool[$resource->id]    = $this->getResourcesRequiredToAddTool($resource->id);
            $this->resourcesNeededToAddForeman[$resource->id] = $this->getResourcesRequiredToAddForeman($resource->id);
        }
    }


    public function getImproveMultiplier($id)
    {
        $im = ImproveMultiplier::firstOrNew(['user_id' => auth()->id(), 'resource_id' => $id]);
        if ($im->amount == 0) {
            $initialValues = [
                1  => 2,
                2  => 3,
                3  => 10,
                4  => 12,
                5  => 20,
                6  => 25,
                7  => 26,
                8  => 40,
                9  => 45,
                10 => 51,
                11 => 56,
                12 => 70
            ];
            $im->amount    = $initialValues[$id];
            $im->save();
        }

        return $im->amount;
    }


    public function gatherEnableStatus($id)
    {
        $re = ResourceEnabled::firstOrCreate(['user_id' => auth()->id(), 'resource_id' => $id]);
        if ($id === 1 && $re->status !== true) {
            $re->status = true;
            $re->save();
        }
        $this->enabled[$id] = $re->status;
    }

    public function bankDeposit($userId, $resourceId, $bAmount, $exAmount) {
        $this->totals[$resourceId] = 0;
        $this->updateAllStatus();
    }


    /*
     * take requests from children
     */

    /**
     * note: add the current resourceIncrementAmount for a resource to it's total and check if that allows other
     * resources to be enabled
     *
     * @param $id
     */
    public function requestGather($id, $multiplier = 1)
    {
        if ($this->enabled[$id]) {

            $tr         = \App\Models\TotalResources::where(['user_id' => auth()->id(), 'resource_id' => $id])->first();
            $tr->amount += $this->resourceGatherAmount[$id]* $multiplier;
            $tr->save();
            $this->totals[$id] = $tr->amount;

            $this->updateAllStatus();
        }
    }


    public function requestEnable($id)
    {
        $this->updateStatus($id);
        if ($this->eligibleToEnable[$id]) {
            foreach ($this->resourcesNeededToEnable[$id] as $rId => $rAmount) {
                $this->totals[$rId] -= $rAmount;
            }
            $re         = ResourceEnabled::where(['user_id' => auth()->id(), 'resource_id' => $id])->first();
            $re->status = true;
            $re->save();
            $this->enabled[$id] = $re->status;
            $this->updateResourceTypeTotal($id, 'worker');
            $this->setStatus('enable', $id);
        }
    }


    public function requestAutomate($id)
    {
        $this->updateStatus($id);
        if ($this->eligibleToAutomate[$id]) {
            foreach ($this->resourcesNeededToAutomate[$id] as $rId => $rAmount) {
                $this->totals[$rId] -= $rAmount;
            }
            $ra         = ResourceAutomated::where(['user_id' => auth()->id(), 'resource_id' => $id])->first();
            $ra->status = true;
            $ra->save();
            $this->automated[$id] = $ra->status;
            $this->setStatus('automated', $id);
        }
    }


    public function requestAdd($id, $type)
    {
        $this->updateStatus($id);
        if ($this->isEligible($id, $type)) {
            $this->payForAddition($id, $type);
            $this->updateResourcesNeeded($id, $type);
            $this->updateResourceTypeTotal($id, $type);
            $this->updateAllStatus();
        }
    }


    public function runTimedChecks()
    {
        $this->checkAutomated();
        $this->checkDebt();
    }


    /*
     * Take internal actions
     */

    private function updateResourceTypeTotal($id, $type)
    {
        switch ($type) {
            case 'worker' :
                $tw = TotalWorkers::where(['user_id' => auth()->id(), 'resource_id' => $id])->first();
                $tw->amount++;
                $tw->save();
                $this->resourceWorkers[$id] = $tw->amount;
                $resourceTypeAmount         = $this->resourceWorkers[$id];
                $resourcesNeededToAddType   = $this->getResourcesRequiredToAddWorker($id);
                break;
            case 'tool' :
                $tt = TotalTools::where(['user_id' => auth()->id(), 'resource_id' => $id])->first();
                $tt->amount++;
                $tt->save();
                $this->resourceTools[$id] = $tt->amount;
                $resourceTypeAmount       = $this->resourceTools[$id];
                $resourcesNeededToAddType = $this->getResourcesRequiredToAddTool($id);
                break;
            case 'foreman' :
                $tf = TotalForeman::where(['user_id' => auth()->id(), 'resource_id' => $id])->first();
                $tf->amount++;
                $tf->save();
                $this->resourceForemen[$id] = $tf->amount;
                $resourceTypeAmount         = $this->resourceForemen[$id];
                $resourcesNeededToAddType   = $this->getResourcesRequiredToAddForeman($id);
                break;
        }
        $this->updateResourceGatherAmount($id);
        if ( ! empty($resourceTypeAmount) && ! empty($resourcesNeededToAddType)) {
            $this->emit('updateTotal', $id, $type, $resourceTypeAmount, $resourcesNeededToAddType);
        }
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


    /**
     * @param $resouceId
     * @param $type
     * @param $bool
     */
    private function updateEligiblity($resouceId, $type, $bool)
    {
        switch ($type) {
            case 'enable':
                if ( ! $this->enabled[$resouceId]) {
                    $this->eligibleToEnable[$resouceId] = $bool;
                    $this->emit('canBeEnabled', $resouceId, $bool);
                }
                break;
            case 'automate' :
                if ( ! $this->automated[$resouceId]) {
                    $this->eligibleToAutomate[$resouceId] = $bool;
                    $this->emit('canBeAutomated', $resouceId, $bool, $this->resourcesNeededToAutomate[$resouceId]);
                }
                break;
            case 'worker' :
                $this->eligibleToAddWorker[$resouceId] = $bool;
                $this->emit('canAddWorker', $resouceId, $bool, $this->resourcesNeededToAddWorker[$resouceId]);
                break;
            case 'tool' :
                $this->eligibleToAddTool[$resouceId] = $bool;
                $this->emit('canAddTool', $resouceId, $bool, $this->resourcesNeededToAddTool[$resouceId]);
                break;
            case 'foreman' :
                $this->eligibleToAddForeman[$resouceId] = $bool;
                $this->emit('canAddForeman', $resouceId, $bool, $this->resourcesNeededToAddForeman[$resouceId]);
                break;
        }
    }


    private function setStatus($type, $id)
    {
        switch ($type) {
            case 'worker' :
                if ($this->enabled[$id]) {
                    $canAdd = ($this->totals[$id] >= $this->resourcesNeededToAddWorker[$id]);
                    $this->updateEligiblity($id, $type, $canAdd);
                }
                break;
            case 'tool' :
                if ($this->enabled[$id]) {
                    $canAdd = ($this->totals[$id] >= $this->resourcesNeededToAddTool[$id]);
                    $this->updateEligiblity($id, $type, $canAdd);
                }
                break;
            case 'foreman' :
                if ($this->enabled[$id]) {
                    $canAdd = ($this->totals[$id] >= $this->resourcesNeededToAddForeman[$id]);
                    $this->updateEligiblity($id, $type, $canAdd);
                }
                break;
            case 'canBeEnabled' :
                if ( ! $this->enabled[$id]) {

                    $data      = $this->resourcesNeededToEnable;
                    $canEnable = $this->hasResourcesNeeded($data);
                    $this->updateEligiblity($id, 'enable', $canEnable);
                }
                break;
            case 'canBeAutomated' :
                if ($this->enabled[$id] && ! $this->automated[$id]) {
                    $data        = $this->resourcesNeededToAutomate;
                    $canAutomate = $this->hasResourcesNeeded($data);
                    $this->updateEligiblity($id, 'automate', $canAutomate);
                }
                break;
            case 'automate' :
                if ($this->eligibleToAutomate[$id]) {
                    $this->automated[$id] = true;
                    $this->emit('toggleAutomate', $id, true);
                }
                break;
            case 'enable' :
                if ($this->eligibleToEnable[$id]) {
                    $this->enabled[$id] = true;
                    $this->emit('toggleEnable', $id, true);
                }
                break;
        }
    }


    private function runAutomatedUpdates($id)
    {
        $this->requestGather($id, 5);
    }


    private function updateResourcesNeeded($id, $type)
    {
        switch ($type) {
            case 'worker' :
                $this->resourcesNeededToAddWorker[$id] += $this->resourcesNeededToAddWorker[$id] * $this->improveMultiplier[$id];
                break;
            case 'tool' :
                $this->resourcesNeededToAddTool[$id] += $this->resourcesNeededToAddTool[$id] * $this->improveMultiplier[$id];
                break;
            case 'foreman' :
                $this->resourcesNeededToAddForeman[$id] += $this->resourcesNeededToAddForeman[$id] * $this->improveMultiplier[$id];
                break;
        }
    }


    private function addDebt($id)
    {
        $this->totals[$id] -= ($this->resourceWorkers[$id] - 1);
    }


    private function updateStatus($id)
    {
        $this->setStatus('canBeEnabled', $id);
        $this->setStatus('canBeAutomated', $id);
        $this->setStatus('worker', $id);
        $this->setStatus('tool', $id);
        $this->setStatus('foreman', $id);
    }


    private function updateAllStatus()
    {
        for ($i = 1; $i <= 12; $i++) {
            $this->updateStatus($i);
        }
    }


    private function checkAutomated()
    {
        foreach ($this->automated as $resourceId => $bool) {
            if ($bool) {
                $this->runAutomatedUpdates($resourceId);
            }
        }
    }


    private function checkDebt()
    {
        foreach ($this->totals as $id => $amount) {
            if ($amount < 0) {
                $this->addDebt($id);
            }
        }
    }


    private function payForAddition($id, $type)
    {
        switch ($type) {
            case 'worker' :
                $this->totals[$id] -= $this->resourcesNeededToAddWorker[$id];
                break;
            case 'tool' :
                $this->totals[$id] -= $this->resourcesNeededToAddTool[$id];
                break;
            case 'foreman' :
                $this->totals[$id] -= $this->resourcesNeededToAddForeman[$id];
                break;
        }
    }



    /*
     * verify validity of actions
     */

    /**
     * @param $resourcesNeeded
     *
     * @return bool
     */
    private function hasResourcesNeeded($resourcesNeeded): bool
    {
        $allow = true;
        foreach ($resourcesNeeded as $neededId => $amountNeeded) {
            $allow = ($this->totals[$neededId] >= $amountNeeded);
            if ($allow === false) {
                break;
            }
        }

        return $allow;
    }


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


    /*
     * default render
     */

    public function render()
    {
        return view('livewire.total-resources');
    }
}
