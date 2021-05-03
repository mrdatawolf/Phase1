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
     * note: this tells us if the user has gathered enough resources to allow resource(s) gathering to be improved
     *
     * @var false[]|mixed
     */
    public $eligibleToImprove;

    /**
     * note: resources needed to enable gathering of a resource
     * note2: resource id to enable [ resource needed =>amount of the resouce needed ]
     */
    public $resourcesNeededToEnable;

    /**
     * note: resources needed to automate gathering of a resource
     * note2: resource id to automate [ resource needed =>amount of the resouce needed ]
     */
    public $resourcesNeededToAutomate;

    /**
     * note: resources needed to improve gathering of a resource
     * note2: resource id to improve [ resource needed =>amount of the resource needed ]
     */
    public $resourcesNeededToImprove;

    /**
     * note: resources needed to improve gathering of a resource
     * note2: resource id to improve [ resource needed =>amount of the resource needed ]
     */
    public $improveMultiplier;

    /**
     * note: amount of resources to increment by
     */
    public $resourceIncrementAmount;

    public $enabled;
    public $automated;
    public $resources;

    public $listeners = ['requestGather', 'requestEnable', 'requestAutomate', 'requestImprove', 'checkAutomated', 'checkDebt'];


    public function mount()
    {
        foreach(Resource::get() as $resource) {
            $this->resources[$resource->id] = $resource->name;
        }

        $this->totals                    = [
            1  => 0,
            2  => 0,
            3  => 0,
            4  => 0,
            5  => 0,
            6  => 0,
            7  => 0,
            8  => 0,
            9  => 0,
            10 => 0,
            11 => 0,
            12 => 0
        ];

        $this->resourcesNeededToImprove = [
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

        $this->resourceIncrementAmount = [
            1  => 1,
            2  => 1,
            3  => 1,
            4  => 1,
            5  => 1,
            6  => 1,
            7  => 1,
            8  => 1,
            9  => 1,
            10 => 1,
            11 => 1,
            12 => 1
        ];

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

        $this->eligibleToEnable          = [
            1  => false,
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
        $this->eligibleToAutomate        = [
            1  => false,
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
        $this->eligibleToImprove        = [
            1  => true,
            2  => true,
            3  => true,
            4  => true,
            5  => true,
            6  => true,
            7  => true,
            8  => true,
            9  => true,
            10 => true,
            11 => true,
            12 => true
        ];
        $this->enabled                   = [
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

        $this->automated = [
            1  => false,
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

        $this->resources = Resource::get();
        $resourcesNeededToAutomate = AutomateResources::get();
        foreach($resourcesNeededToAutomate as $data) {
            for ($x = 1; $x <= 12; $x++) {
                $thisId = 'r'.$x;
                $amount = (int)$data->$thisId;
                if ($amount > 0) {
                    $this->resourcesNeededToAutomate[$data->resource_id][$x] = $amount;
                }
            }
        }

        $resourcesNeededToEnable = EnableResources::get();
        foreach($resourcesNeededToEnable as $data) {
            for ($x = 1; $x <= 12; $x++) {
                $thisId = 'r'.$x;
                $amount = (int)$data->$thisId;
                if ($amount > 0) {
                    $this->resourcesNeededToEnable[$data->resource_id][$x] = $amount;
                }
            }
        }
    }


    private function setEnableStatus()
    {
        foreach ($this->resourcesNeededToEnable as $resouceId => $data) {
            if ( ! $this->enabled[$resouceId]) {
                $canEnable = $this->hasResourcesNeeded($data);
                $this->updateEligiblity($resouceId, 'enable', $canEnable);
            }
        }
    }


    private function setAutomateStatus()
    {
        foreach ($this->resourcesNeededToAutomate as $resouceId => $data) {
            if ( $this->enabled[$resouceId] && ! $this->automated[$resouceId]) {
                $canAutomate = $this->hasResourcesNeeded($data);
                $this->updateEligiblity($resouceId, 'automate', $canAutomate);
            }
        }
    }

    private function setImproveStatus()
    {
        foreach ($this->resourcesNeededToImprove as $resourceId => $data) {
            if ( $this->enabled[$resourceId]) {
                $canImprove = ($this->totals[$resourceId] >= $data);
                $this->updateEligiblity($resourceId, 'improve', $canImprove);
            }
        }
    }


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
     * @param $resouceId
     * @param $type
     * @param $bool
     */
    private function updateEligiblity($resouceId, $type, $bool) {
        switch($type) {
            case 'enable':
                if(! $this->enabled[$resouceId]) {
                    $this->eligibleToEnable[$resouceId] = $bool;
                    $this->emit('canBeEnabled', $resouceId, $bool);
                }
                break;
            case 'automate' :
                if(! $this->automated[$resouceId]) {
                    $this->eligibleToAutomate[$resouceId] = $bool;
                    $this->emit('canBeAutomated', $resouceId, $bool, $this->resourcesNeededToAutomate[$resouceId]);
                }
                break;
            default :
                $this->eligibleToImprove[$resouceId] = $bool;
                $this->emit('canBeImproved', $resouceId, $bool, $this->resourcesNeededToImprove[$resouceId]);
                break;
        }
    }

    private function runAutomatedUpdates($id) {
        $this->totals[$id] += $this->resourceIncrementAmount[$id]*5;
    }

    private function setResourcesNeededToImprove($id) {
        $this->resourcesNeededToImprove[$id] = $this->resourcesNeededToImprove[$id] * $this->improveMultiplier[$id];
    }


    private function addDebt($id) {
        $this->totals[$id] -= ($this->resourceIncrementAmount[$id]-1);
    }

    private function updateStatuses() {
        $this->setEnableStatus();
        $this->setAutomateStatus();
        $this->setImproveStatus();
    }

    /**
     * note: add the current resourceIncrementAmount for a resource to it's total and check if that allows other resources to be enabled
     *
     * @param $id
     */
    public function requestGather($id)
    {
        if($this->enabled) {
            $this->totals[$id] += $this->resourceIncrementAmount[$id];
            $this->updateStatuses();
        }
    }


    public function requestEnable($id)
    {
        $this->updateStatuses();
        if($this->eligibleToEnable[$id]) {
            foreach ($this->resourcesNeededToEnable[$id] as $rId => $rAmount) {
                $this->totals[$rId] -= $rAmount;
            }
            $this->enabled[$id] = true;
            $this->resourceIncrementAmount[$id] = 1;
                $this->setEnableStatus();
        }
        $this->emit('updateEnable', $id, true);
    }

    public function requestAutomate($id)
    {
        $this->updateStatuses();
        if($this->eligibleToAutomate[$id]) {
            foreach ($this->resourcesNeededToAutomate[$id] as $rId => $rAmount) {
                $this->totals[$rId] -= $rAmount;
            }
            $this->automated[$id] = true;
            $this->setAutomateStatus();
            $this->emit('updateAutomate', $id, true);
        }
    }

    public function requestImprove($id)
    {
        $this->updateStatuses();
        if($this->eligibleToImprove[$id]) {
            $this->totals[$id] -= $this->resourcesNeededToImprove[$id];
            $this->setImproveStatus();
            $this->resourceIncrementAmount[$id]++;
            $this->setResourcesNeededToImprove($id);
            $this->emit('updateImprove', $id, $this->resourceIncrementAmount[$id], $this->resourcesNeededToImprove[$id]);
        }
    }


    public function checkAutomated() {
        foreach($this->automated as $resourceId => $bool) {
            if($bool) {
                $this->runAutomatedUpdates($resourceId);
            }
        }
    }

    public function checkDebt() {
        foreach ($this->totals as $id => $amount) {
            if($amount < 0) {
                $this->addDebt($id);
            }
        }
    }


    public function runTimedChecks() {
        $this->checkAutomated();
        $this->checkDebt();
    }


    public function render()
    {
        return view('livewire.total-resources');
    }
}
