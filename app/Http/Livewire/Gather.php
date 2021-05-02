<?php namespace App\Http\Livewire;

use App\Models\AutomateResources;
use App\Models\EnableResources;
use App\Models\Resource;
use Livewire\Component;

class Gather extends Component
{
    public $debug = false;
    public $resourceId;
    public $resourceName;
    public $allowed         = false;
    public $gatherAmount              = 1;
    public $improveResourceRequired   = '-';
    public $automateResourcesRequired = '-';
    public $canEnable       = false;
    public $canAutomate     = false;
    public $canImprove      = false;
    public $enabled         = false;
    public $automated       = false;
    public $resourcesNeededToAutomate = [];
    public $resourcesNeededToEnable = [];
    public $resources;
    public $canSell;
    public $canSendToStorage;
    public $canSendToTeamStorage;

    public $listeners       = ['canBeEnabled', 'canBeAutomated', 'canBeImproved', 'updateImprove', 'updateEnable', 'updateAutomate'];

    public function mount() {
        $this->resources = Resource::get();
        $resourcesNeededToAutomate = AutomateResources::where('resource_id',$this->resourceId)->first();
        for($x=1;$x<=12;$x++){
            $thisId = 'r'.$x;
            $amount = (int) $resourcesNeededToAutomate->$thisId;
            if($amount > 0) {
                $this->resourcesNeededToAutomate[$x] = $amount;
            }
        }

        $resourcesNeededToEnable = EnableResources::where('resource_id',$this->resourceId)->first();
        for($x=1;$x<=12;$x++){
            $thisId = 'r'.$x;
            $amount = (int) $resourcesNeededToEnable->$thisId;
            if($amount > 0) {
                $this->resourcesNeededToEnable[$x] = $amount;
            }
        }

        $this->canSell = true;
        $this->canSendToStorage = true;
        $this->canSendToTeamStorage = true;
    }
    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        return $this->allowed;
    }


    /**
     * note: when the parent wants to let us know about a change in the enable possiblity for a resource
     * @param $id
     * @param $bool
     */
    public function canBeEnabled($id, $bool) {
        if($this->resourceId === $id) {
            $this->canEnable = $bool;
        }
    }


    /**
     * note: when the parent wants to let us know about a change in the automate possiblity for a resource
     * @param $id
     * @param $bool
     */
    public function canBeAutomated($id, $bool, $amount) {
        if($this->resourceId === $id) {
            $this->canAutomate = $bool;
            $this->automateResourcesRequired = $amount;
        }
    }


    /**
     * note: when the parent wants to let us know about a change in improve possiblity for a resource
     * @param $id
     * @param $bool
     * @param $amount
     */
    public function canBeImproved($id, $bool, $amount) {
        if($this->resourceId === $id) {
            $this->canImprove              = $bool;
            $this->improveResourceRequired = $amount;
        }
    }

    /**
     * tell the parent we want to add to the total for the resource
     */
    public function gather() {
        if($this->enabled) {
            $this->emit('requestGather', $this->resourceId);
        }
    }

    /**
     * tell the parent we want to improve the resource
     */
    public function improve() {
        if($this->enabled && $this->canImprove) {
            $this->emit('requestImprove', $this->resourceId);
        }
        $this->canImprove = false;
    }


    /**
     * tell the parent we want to enable the resource
     */
    public function enable() {
        if($this->canEnable) {
            $this->enabled = true;
            $this->emit('requestEnable', $this->resourceId);
        }
        $this->canEnable = false;
    }

    /**
     * tell the parent we want to automate the resource
     */
    public function automate() {
        if($this->canAutomate) {
            $this->emit('requestAutomate', $this->resourceId);
        }
    }


    /**
     * note: when the parent says an improve event has happened we update our side
     * @param $id
     * @param $resourceIncrementAmount
     * @param $resourcesNeededToImprove
     */
    public function updateImprove($id, $resourceIncrementAmount, $resourcesNeededToImprove) {
        if($this->resourceId === $id) {
            $this->gatherAmount            = $resourceIncrementAmount;
            $this->improveResourceRequired = $resourcesNeededToImprove;
        }
    }


    /**
     * note: when the parent says an enable event has happened we update our side
     * @param $id
     * @param $bool
     */
    public function updateEnable($id, $bool) {
        if($this->resourceId === $id) {
            $this->enabled = $bool;
        }
    }


    /**
     * note: when the parent says an automate event has happened we update our side
     * @param $id
     * @param $bool
     */
    public function updateAutomate($id, $bool) {
        if($this->resourceId === $id) {
            $this->automated = $bool;
        }
    }

    public function render()
    {
        return view('livewire.gather');
    }
}
