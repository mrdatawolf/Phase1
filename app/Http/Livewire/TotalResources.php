<?php namespace App\Http\Livewire;

/**
 * purpose:
 * 1. hold the total stored amount for each resource.
 * 2. add to the amount of a resource when it's added.
 * 3. define if a given resource is available.
 * 4. set the minimum resources needed to make a resource available.
 */

use App\Http\Traits\RequestTrait;
use App\Http\Traits\Status;
use App\Objects\Automate;
use App\Objects\Foreman;
use App\Models\ImproveMultiplier;
use App\Models\Resource;
use App\Objects\Tool;
use App\Objects\Worker;
use Livewire\Component;
use App\Objects\Gather;

class TotalResources extends Component
{
    use RequestTrait;
    use Status;

    public $totals;

    /**
     * note: resources needed to improve gathering of a resource
     * note2: resource id to improve [ resource needed => amount of the resource needed ]
     */
    public $improveMultiplier;


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
            $automate = new Automate($resource->id);
            $this->automated[$resource->id]                   = $automate->getStatus();
        }
    }


    public function getImproveMultiplier($id)
    {
        $im = ImproveMultiplier::firstOrCreate(['user_id' => auth()->id(), 'resource_id' => $id], ['amount' => config('multipliers.gather')[$id]]);

        return $im->amount;
    }


    public function bankDeposit($resourceId)
    {
        //PUT STUFF HERE
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
            $gather   = new Gather($resource->id);
            $this->totals[$resource->id] = $gather->getAmount();
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
            $workers  = new Worker($i);
            $tools    = new Tool($i);
            $foremen  = new Foreman($i);
            $automate = new Automate($i);
            $this->emit('canBeEnabled', $i, $this->setStatus('canBeEnabled', $i));
            $this->emit('canBeAutomated', $i, $this->setStatus('canBeAutomated', $i), $automate->getCost());
            $this->emit('canAddWorker', $i, $this->setStatus('worker', $i), $workers->getCost());
            $this->emit('canAddTool', $i, $this->setStatus('tool', $i), $tools->getCost());
            $this->emit('canAddForeman', $i, $this->setStatus('foreman', $i), $foremen->getCost());
        }
    }


    private function addDebt($id)
    {
        $workers  = new Worker($id);
        $this->totals[$id] -= ($workers->getAmount() - 1);
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
     * default render
     */

    public function render()
    {
        return view('livewire.total-resources');
    }
}
