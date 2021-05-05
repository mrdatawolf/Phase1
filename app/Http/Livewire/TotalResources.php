<?php namespace App\Http\Livewire;

/**
 * purpose:
 * 1. hold the total stored amount for each resource.
 * 2. add to the amount of a resource when it's added.
 * 3. define if a given resource is available.
 * 4. set the minimum resources needed to make a resource available.
 */

use App\Models\AutomateResources;
use App\Models\EnableResources;
use App\Models\Resource;
use Livewire\Component;

class TotalResources extends Component
{
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

    public $listeners = [
        'requestGather',
        'requestEnable',
        'requestAutomate',
        'requestAdd',
        'checkAutomated',
        'checkDebt'
    ];


    public function mount()
    {
        foreach (Resource::get() as $resource) {
            $this->resources[$resource->id] = $resource->name;
        }

        for ($i = 1; $i <= 12; $i++) {
            $this->totals[$i]                       = 0;
            $this->resourceWorkers[$i]              = 1;
            $this->resourceTools[$i]                = 1;
            $this->resourceForemen[$i]              = 1;
            $this->resourceGatherAmount[$i]         = 1;
            $this->eligibleToEnable[$i]             = false;
            $this->eligibleToAutomate[$i]           = false;
            $this->automated[$i]                    = false;
            $this->eligibleToAddWorker[$i]          = true;
            $this->eligibleToAddTool[$i]            = true;
            $this->eligibleToAddForeman[$i]         = true;
        }
        $genericNeeds = [
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
        for ($i = 1; $i <= 12; $i++) {
            $this->resourcesNeededToAddWorker[$i]  = $genericNeeds[$i];
            $this->resourcesNeededToAddTool[$i]    = $genericNeeds[$i];
            $this->resourcesNeededToAddForeman[$i] = $genericNeeds[$i];
        }

        $this->improveMultiplier = [
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

        $this->enabled = [
            1  => true,
            2  => false,
            3  => false,
            4  => false,
            5  => false,
            6  => false,
            7  => false,
            8  => false,
            9  => false,
            10 => false,
            11 => false,
            12 => false
        ];

        $this->resources           = Resource::get();
        $resourcesNeededToAutomate = AutomateResources::get();
        foreach ($resourcesNeededToAutomate as $data) {
            for ($x = 1; $x <= 12; $x++) {
                $thisId = 'r'.$x;
                $amount = (int)$data->$thisId;
                if ($amount > 0) {
                    $this->resourcesNeededToAutomate[$data->resource_id][$x] = $amount;
                }
            }
        }

        $resourcesNeededToEnable = EnableResources::get();
        foreach ($resourcesNeededToEnable as $data) {
            for ($x = 1; $x <= 12; $x++) {
                $thisId = 'r'.$x;
                $amount = (int)$data->$thisId;
                if ($amount > 0) {
                    $this->resourcesNeededToEnable[$data->resource_id][$x] = $amount;
                }
            }
        }
    }


    private function setEnableStatus($id)
    {
        if ( ! $this->enabled[$id]) {
            $data      = $this->resourcesNeededToEnable[$id];
            $canEnable = $this->hasResourcesNeeded($data);
            $this->updateEligiblity($id, 'enable', $canEnable);
            $this->emit('updateEnable', $id, true);
        }
    }


    private function setAutomateStatus($id)
    {
        if ($this->enabled[$id] && ! $this->automated[$id]) {
            $data        = $this->resourcesNeededToAutomate[$id];
            $canAutomate = $this->hasResourcesNeeded($data);
            $this->updateEligiblity($id, 'automate', $canAutomate);
        }
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
    public function requestGather($id)
    {
        if ($this->enabled[$id]) {
            $this->totals[$id] += $this->resourceGatherAmount[$id];
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
            $this->enabled[$id]         = true;
            $this->resourceWorkers[$id] = 1;
            $this->setEnableStatus($id);
        }
    }


    public function requestAutomate($id)
    {
        $this->updateStatus($id);
        if ($this->eligibleToAutomate[$id]) {
            foreach ($this->resourcesNeededToAutomate[$id] as $rId => $rAmount) {
                $this->totals[$rId] -= $rAmount;
            }
            $this->automated[$id] = true;
            $this->setAutomateStatus($id);
            $this->emit('updateAutomate', $id, true);
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
                $this->resourceWorkers[$id]++;
                $resourceTypeAmount       = $this->resourceWorkers[$id];
                $resourcesNeededToAddType = $this->resourcesNeededToAddWorker[$id];
                break;
            case 'tool' :
                $this->resourceTools[$id]++;
                $resourceTypeAmount       = $this->resourceTools[$id];
                $resourcesNeededToAddType = $this->resourcesNeededToAddTool[$id];
                break;
            case 'foreman' :
                $this->resourceForemen[$id]++;
                $resourceTypeAmount       = $this->resourceForemen[$id];
                $resourcesNeededToAddType = $this->resourcesNeededToAddForeman[$id];
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
                $this->emit('canAddTool', $resouceId, $bool, $this->resourcesNeededToAddWorker[$resouceId]);
                break;
            case 'foreman' :
                $this->eligibleToAddForeman[$resouceId] = $bool;
                $this->emit('canAddForeman', $resouceId, $bool, $this->resourcesNeededToAddWorker[$resouceId]);
                break;
        }
    }


    private function setStatus($type)
    {
        switch ($type) {
            case 'worker' :
                foreach ($this->resourcesNeededToAddWorker as $resourceId => $data) {
                    if ($this->enabled[$resourceId]) {
                        $canAdd = ($this->totals[$resourceId] >= $data);
                        $this->updateEligiblity($resourceId, $type, $canAdd);
                    }
                }
                break;
            case 'tool' :
                foreach ($this->resourcesNeededToAddTool as $resourceId => $data) {
                    if ($this->enabled[$resourceId]) {
                        $canAdd = ($this->totals[$resourceId] >= $data);
                        $this->updateEligiblity($resourceId, $type, $canAdd);
                    }
                }
                break;
            case 'foreman' :
                foreach ($this->resourcesNeededToAddForeman as $resourceId => $data) {
                    if ($this->enabled[$resourceId]) {
                        $canAdd = ($this->totals[$resourceId] >= $data);
                        $this->updateEligiblity($resourceId, $type, $canAdd);
                    }
                }
                break;
        }
    }


    private function runAutomatedUpdates($id)
    {
        $this->totals[$id] += $this->resourceWorkers[$id] * 5;
    }


    private function updateResourcesNeeded($id, $type)
    {
        switch ($type) {
            case 'worker' :
                $this->resourcesNeededToAddWorker[$id] = $this->resourcesNeededToAddWorker[$id] * $this->improveMultiplier[$id];
                break;
            case 'tool' :
                $this->resourcesNeededToAddTool[$id] = $this->resourcesNeededToAddTool[$id] * $this->improveMultiplier[$id];
                break;
            case 'foreman' :
                $this->resourcesNeededToAddForeman[$id] = $this->resourcesNeededToAddForeman[$id] * $this->improveMultiplier[$id];
                break;
        }
    }


    private function addDebt($id)
    {
        $this->totals[$id] -= ($this->resourceWorkers[$id] - 1);
    }


    private function updateStatus($id)
    {
        $this->setEnableStatus($id);
        $this->setAutomateStatus($id);
        $this->setStatus('worker');
        $this->setStatus('tool');
        $this->setStatus('foreman');
    }

    private function updateAllStatus() {
        for($i=1;$i<=12;$i++) {
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


    private function payForAddition($id, $type) {
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
