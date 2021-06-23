<?php namespace App\Http\Livewire;

use App\Http\Traits\ResourcesRequired;
use App\Models\Resource as ResourceModel;
use Livewire\Component;
use App\Objects\Resource;

class Gather extends Component
{
    use ResourcesRequired;

    public $resourceId;
    public $resourceName;
    public $debug                         = false;
    public $allowed                       = false;
    public $allowEnable                   = false;
    public $allowAutomate                 = false;
    public $allowAddWorker                = false;
    public $allowAddTool                  = false;
    public $allowAddForeman               = false;
    public $enabled                       = false;
    public $automated                     = false;
    public $resourcesNeededToAutomate     = [];
    public $resourcesNeededToEnable       = [];
    public $totalGatherAmount             = 1;
    public $totalWorkers                  = 1;
    public $totalTools                    = 0;
    public $totalForemen                  = 0;
    public $totalResource                 = 0;
    public $automateResourcesRequired     = ' ';
    public $resourcesRequiredToAddWorker  = ' ';
    public $resourcesRequiredToAddTool    = ' ';
    public $resourcesRequiredToAddForeman = ' ';
    public $resources;
    public $allowSell;
    public $allowSendToStorage;
    public $allowSendToTeamStorage;

    public $listeners = [
        'canBeEnabled',
        'canBeAutomated',
        'canAddWorker',
        'canAddTool',
        'canAddForeman',
        'toggleEnable',
        'toggleAutomate',
        'updateTotal'
    ];


    public function mount()
    {
        $resource                     = new Resource($this->resourceId);
        $this->resources              = ResourceModel::all();
        $this->allowSell              = false;
        $this->allowSendToStorage     = false;
        $this->allowSendToTeamStorage = false;

        $this->allowAutomate                 = $resource->getEligibleToAutomate();
        $this->automated                     = $resource->getAutomated();
        $this->enabled                       = $resource->getActivated();
        $this->resourcesNeededToAutomate     = $resource->getAutomateCost();
        $this->resourcesNeededToEnable       = $resource->getActivateCost();
        $this->totalGatherAmount             = $resource->getIncrementAmount();
        $this->resourcesRequiredToAddWorker  = $resource->getWorkerCost();
        $this->resourcesRequiredToAddTool    = $resource->getToolCost();
        $this->resourcesRequiredToAddForeman = $resource->getForemanCost();

        $this->totalResource   = $resource->getAmount();
        $this->totalForemen    = $resource->getForemanAmount();
        $this->totalTools      = $resource->getToolAmount();
        $this->totalWorkers    = $resource->getWorkerAmount();
        $this->allowAddForeman = $resource->getEligibleToAddForeman();
        $this->allowAddTool    = $resource->getEligibleToAddTool();
        $this->allowAddWorker  = $resource->getEligibleToAddWorker();
        $this->allowAutomate   = $resource->getEligibleToAutomate();
        $this->allowEnable     = $resource->getEligibleToActivate();
    }


    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        return $this->allowed;
    }


    /** LISTENERS */

    /**
     * note: when the parent wants to let us know about a change in the enable possiblity for a resource
     *
     * @param $id
     * @param $bool
     */
    public function canBeEnabled($id, $bool)
    {
        if ($this->resourceId === $id) {
            $this->allowEnable = $bool;
        }
    }


    /**
     * note: when the parent wants to let us know about a change in the automate possiblity for a resource
     *
     * @param $id
     * @param $bool
     * @param $amount
     */
    public function canBeAutomated($id, $bool, $amount)
    {
        if ($this->resourceId === $id) {

            $this->allowAutomate             = $bool;
            $this->automateResourcesRequired = $amount;
        }
    }


    /**
     * note: when the parent wants to let us know about a change in improve possiblity for a resource
     *
     * @param $id
     * @param $bool
     * @param $amount
     */
    public function canAddWorker($id, $bool, $amount)
    {
        if ($this->resourceId === $id) {
            $this->allowAddWorker               = $bool;
            $this->resourcesRequiredToAddWorker = $amount;
        }
    }


    /**
     * note: when the parent wants to let us know about a change in improve possiblity for a resource
     *
     * @param $id
     * @param $bool
     * @param $amount
     */
    public function canAddTool($id, $bool, $amount)
    {
        if ($this->resourceId === $id) {
            $this->allowAddTool               = $bool;
            $this->resourcesRequiredToAddTool = $amount;
        }
    }


    /**
     * note: when the parent wants to let us know about a change in improve possiblity for a resource
     *
     * @param $id
     * @param $bool
     * @param $amount
     */
    public function canAddForeman($id, $bool, $amount)
    {
        if ($this->resourceId === $id) {
            $this->allowAddForeman               = $bool;
            $this->resourcesRequiredToAddForeman = $amount;
        }
    }


    /**
     * note: when the parent says the total of a type has changed
     *
     * @param int    $id
     * @param string $type
     * @param int    $resourceTypeAmount
     * @param int    $resourcesNeededToAddType
     */
    public function updateTotal(int $id, string $type, int $resourceTypeAmount, int $resourcesNeededToAddType)
    {
        if ($this->resourceId === $id) {
            switch ($type) {
                case 'gather':
                    $this->totalGatherAmount = $resourceTypeAmount;
                    break;
                case 'worker':
                    $this->totalWorkers                 = $resourceTypeAmount;
                    $this->resourcesRequiredToAddWorker = $resourcesNeededToAddType;
                    break;
                case 'tool':
                    $this->totalTools                 = $resourceTypeAmount;
                    $this->resourcesRequiredToAddTool = $resourcesNeededToAddType;
                    break;
                case 'foreman':
                    $this->totalForemen                  = $resourceTypeAmount;
                    $this->resourcesRequiredToAddForeman = $resourcesNeededToAddType;
                    break;
            }
        }
    }


    /**
     * note: when the parent says an enable event has happened we update our side
     *
     * @param $id
     * @param $bool
     */
    public function toggleEnable($id, $bool)
    {
        if ($this->resourceId === $id) {
            $this->enabled = $bool;
        }
    }


    /**
     * note: when the parent says an automate event has happened we update our side
     *
     * @param $id
     * @param $bool
     */
    public function toggleAutomate($id, $bool)
    {
        if ($this->resourceId === $id) {
            $this->automated = $bool;
        }
    }

    /** request out to the system */

    /**
     * tell the parent we want to add to the total for the resource
     */
    public function gather()
    {
        $this->emit('requestGather', $this->resourceId);
    }


    /**
     * tell the parent we want to improve the resource
     */
    public function addWorker()
    {
        $this->allowAddWorker = false;
        $this->emit('requestAdd', $this->resourceId, 'worker');
    }


    /**
     * tell the parent we want to improve the resource
     */
    public function addTool()
    {
        $this->allowAddTool = false;
        $this->emit('requestAdd', $this->resourceId, 'tool');
    }


    /**
     * tell the parent we want to improve the resource
     */
    public function addForeman()
    {
        $this->allowAddForeman = false;
        $this->emit('requestAdd', $this->resourceId, 'foreman');
    }


    /**
     * tell the parent we want to enable the resource
     */
    public function enable()
    {
        $this->allowEnable = false;
        $this->emit('requestEnable', $this->resourceId);
    }


    /**
     * tell the parent we want to automate the resource
     */
    public function automate()
    {
        $this->emit('requestAutomate', $this->resourceId);
    }


    /**
     * tell the parent we want to sell the resource
     */
    public function sellRequest()
    {
        $this->emit('sellRequest', auth()->id(), $this->resourceId);
    }


    public function storageRequest()
    {
        //do stuff
    }


    public function factionStorageRequest()
    {
        //do stuff
    }


    public function render()
    {
        return view('livewire.gather');
    }
}
