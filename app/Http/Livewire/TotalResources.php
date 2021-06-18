<?php namespace App\Http\Livewire;

/**
 * purpose:
 * 1. hold the total stored amount for each resource.
 * 2. add to the amount of a resource when it's added.
 * 3. define if a given resource is available.
 * 4. set the minimum resources needed to make a resource available.
 */

use App\Http\Traits\InitialState;
use App\Http\Traits\IsEligibleTo;
use App\Http\Traits\PayFor;
use App\Http\Traits\RequestTrait;
use App\Http\Traits\Status;
use App\Http\Traits\UpdateResourceTotal;
use App\Models\ImproveMultiplier;
use App\Models\Resource;
use App\Models\ResourceEnabled;
use Livewire\Component;

class TotalResources extends Component
{
    use InitialState;
    use IsEligibleTo;
    use UpdateResourceTotal;
    use PayFor;
    use RequestTrait;
    use Status;

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
    public $resourcesNeededToEnable = [];

    /**
     * note: resources needed to automate gathering of a resource
     * note2: resource id to automate [ resource needed => amount of the resouce needed ]
     */
    public $resourcesNeededToAutomate = [];

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
        'checkDebt',
        'bankDeposit'
    ];


    public function mount()
    {
        $this->resources = Resource::get();
        $this->updateCurrentResourceTotals();
        foreach ($this->resources as $resource) {
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
            $this->resourcesNeededToAutomate[$resource->id]   = $this->getResourcesNeededToAutomate($resource->id);
            $this->resourcesNeededToEnable[$resource->id]     = $this->getResourcesNeededToEnable($resource->id);
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


    public function bankDeposit($userId, $resourceId, $bAmount, $exAmount)
    {
        $this->totals[$resourceId] = 0;
        $this->updateAllStatus();
    }


    /*
     * take requests from children
     */


    public function runTimedChecks()
    {
        $this->checkAutomated();
        $this->checkDebt();
    }


    /*
     * Take internal actions
     */

    private function updateCurrentResourceTotals()
    {
        foreach ($this->resources as $resource) {
            $this->totals[$resource->id] = $this->gatherTotalResource(auth()->id(), $resource->id);
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
