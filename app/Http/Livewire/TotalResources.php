<?php namespace App\Http\Livewire;

/**
 * purpose:
 * 1. hold the total stored amount for each resource.
 * 2. add to the amount of a resource when it's added.
 * 3. define if a given resource is available.
 * 4. set the minimum resources needed to make a resource available.
 */

use App\Http\Traits\PayFor;
use App\Http\Traits\RequestTrait;
use App\Http\Traits\Status;
use App\Http\Traits\UpdateResourceTotal;
use App\Models\Automate;
use App\Models\Enable;
use App\Models\Foreman;
use App\Models\ImproveMultiplier;
use App\Models\Resource;
use App\Models\Tool;
use App\Models\Worker;
use Livewire\Component;

class TotalResources extends Component
{
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
            $workers  = new Worker($resource->id);
            $tools    = new Tool($resource->id);
            $foremen  = new Foreman($resource->id);
            $gather   = new \App\Models\Gather($resource->id);
            $enable   = new Enable($resource->id);
            $automate = new Automate($resource->id);

            $this->resourceWorkers[$resource->id]             = $workers->getAmount();
            $this->resourceTools[$resource->id]               = $tools->getAmount();
            $this->resourceForemen[$resource->id]             = $foremen->getAmount();
            $this->resourceGatherAmount[$resource->id]        = $gather->getAmount();
            $this->eligibleToEnable[$resource->id]            = $enable->getEligibleToActivate();
            $this->eligibleToAutomate[$resource->id]          = $automate->getEligibleToActivate();
            $this->eligibleToAddWorker[$resource->id]         = $workers->getEligibleToAdd();
            $this->eligibleToAddTool[$resource->id]           = $tools->getEligibleToAdd();
            $this->eligibleToAddForeman[$resource->id]        = $foremen->getEligibleToAdd();
            $this->automated[$resource->id]                   = $automate->getStatus();
            $this->improveMultiplier[$resource->id]           = $this->getImproveMultiplier($resource->id);
            $this->enabled[$resource->id]                     = $enable->getStatus();
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
            $initialValues = config('multipliers.gather');
            $im->amount    = $initialValues[$id];
            $im->save();
        }

        return $im->amount;
    }


    public function bankDeposit($resourceId)
    {
        $this->totals[$resourceId] = 0;
        $resourceCount             = Resource::count();
        for ($i = 1; $i <= $resourceCount; $i++) {
            $result = $this->setStatus('canBeEnabled', $i);
            $this->emit('canBeEnabled', $i, $result);
            $result = $this->setStatus('canBeAutomated', $i);
            $this->emit('canBeAutomated', $i, $result, $this->resourcesNeededToAutomate[$i]);
            $result = $this->setStatus('worker', $i);
            $this->emit('canAddWorker', $i, $result, $this->resourcesNeededToAddWorker[$i]);
            $result = $this->setStatus('tool', $i);
            $this->emit('canAddTool', $i, $result, $this->resourcesNeededToAddTool[$i]);
            $result = $this->setStatus('foreman', $i);
            $this->emit('canAddForeman', $i, $result, $this->resourcesNeededToAddForeman[$i]);
        }
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


    /**
     * @param $resourceId
     */
    private function runAutomatedUpdates($resourceId)
    {
        $amount                    = $this->requestGather($resourceId, 5);
        $this->totals[$resourceId] = $amount;
        $resourceCount             = Resource::count();
        for ($i = 1; $i <= $resourceCount; $i++) {
            $result = $this->setStatus('canBeEnabled', $i);
            $this->emit('canBeEnabled', $i, $result);
            $result = $this->setStatus('canBeAutomated', $i);
            $this->emit('canBeAutomated', $i, $result, $this->resourcesNeededToAutomate[$i]);
            $result = $this->setStatus('worker', $i);
            $this->emit('canAddWorker', $i, $result, $this->resourcesNeededToAddWorker[$i]);
            $result = $this->setStatus('tool', $i);
            $this->emit('canAddTool', $i, $result, $this->resourcesNeededToAddTool[$i]);
            $result = $this->setStatus('foreman', $i);
            $this->emit('canAddForeman', $i, $result, $this->resourcesNeededToAddForeman[$i]);
        }
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
