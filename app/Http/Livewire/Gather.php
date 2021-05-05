<?php namespace App\Http\Livewire;

use App\Models\AutomateResources;
use App\Models\EnableResources;
use App\Models\Resource;
use Livewire\Component;

class Gather extends Component
{
    public $resourceId;
    public $resourceName;
    public $debug                      = false;
    public $allowed                    = false;
    public $allowEnable                = false;
    public $allowAutomate              = false;
    public $allowAddWorker             = false;
    public $allowAddTool               = false;
    public $allowAddForeman            = false;
    public $enabled                    = false;
    public $automated                  = false;
    public $resourcesNeededToAutomate  = [];
    public $resourcesNeededToEnable    = [];
    public $totalGather                = 1;
    public $totalWorkers               = 1;
    public $totalTools                 = 0;
    public $totalForemen               = 0;
    public $automateResourcesRequired  = ' ';
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
        $this->resources           = Resource::get();
        $resourcesNeededToAutomate = AutomateResources::where('resource_id', $this->resourceId)->first();
        for ($x = 1; $x <= 12; $x++) {
            $thisId = 'r'.$x;
            $amount = (int)$resourcesNeededToAutomate->$thisId;
            if ($amount > 0) {
                $this->resourcesNeededToAutomate[$x] = $amount;
            }
        }

        $resourcesNeededToEnable = EnableResources::where('resource_id', $this->resourceId)->first();
        for ($x = 1; $x <= 12; $x++) {
            $thisId = 'r'.$x;
            $amount = (int)$resourcesNeededToEnable->$thisId;
            if ($amount > 0) {
                $this->resourcesNeededToEnable[$x] = $amount;
            }
        }

        $this->allowSell              = false;
        $this->allowSendToStorage     = false;
        $this->allowSendToTeamStorage = false;
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
            $this->allowAddWorker            = $bool;
            $this->addWorkerResourceRequired = $amount;
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
            $this->allowAddTool              = $bool;
            $this->addWorkerResourceRequired = $amount;
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
            $this->allowAddForeman           = $bool;
            $this->addWorkerResourceRequired = $amount;
        }
    }


    /**
     * tell the parent we want to add to the total for the resource
     */
    public function gather()
    {
        if ($this->enabled) {
            $this->emit('requestGather', $this->resourceId);
        }
    }


    /**
     * tell the parent we want to improve the resource
     */
    public function addWorker()
    {
        if ($this->enabled && $this->allowAddWorker) {
            $this->emit('requestAdd', $this->resourceId, 'worker');
        }
        $this->allowAddWorker = false;
    }


    /**
     * tell the parent we want to improve the resource
     */
    public function addTool()
    {
        if ($this->enabled && $this->allowAddTool) {
            $this->emit('requestAdd', $this->resourceId, 'tool');
        }
        $this->allowAddTool = false;
    }


    /**
     * tell the parent we want to improve the resource
     */
    public function addForeman()
    {
        if ($this->enabled && $this->allowAddForeman) {
            $this->emit('requestAdd', $this->resourceId, 'foreman');
        }
        $this->allowAddForeman = false;
    }


    /**
     * tell the parent we want to enable the resource
     */
    public function enable()
    {
        if ($this->allowEnable) {
            $this->enabled = true;
            $this->emit('requestEnable', $this->resourceId);
        }
        $this->allowEnable = false;
    }


    /**
     * tell the parent we want to automate the resource
     */
    public function automate()
    {
        if ($this->allowAutomate) {
            $this->emit('requestAutomate', $this->resourceId);
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
                    $this->totalGather = $resourceTypeAmount;
                    break;
                case 'worker':
                    $this->totalWorkers                 = $resourceTypeAmount;
                    $this->resourcesRequiredToAddWorker = $resourcesNeededToAddType;
                    break;
                case 'tool':
                    $this->totalTools = $resourceTypeAmount;
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


    public function render()
    {
        return view('livewire.gather');
    }
}
