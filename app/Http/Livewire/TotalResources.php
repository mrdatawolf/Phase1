<?php namespace App\Http\Livewire;

/**
 * purpose:
 * 1. hold the total stored amount for each resource.
 * 2. add to the amount of a resource when it's added.
 * 3. define if a given resource is available.
 * 4. set the minimum resources needed to make a resource available.
 */

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

    public $enabled;
    public $automated;

    public $listeners = ['addToTotal', 'enable', 'automate', 'improve'];


    public function mount()
    {
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
        $this->resourcesNeededToEnable   = [
            1  => [],
            2  => [1 => 10],
            3  => [2 => 5, 1 => 10],
            4  => [],
            5  => [],
            6  => [],
            7  => [],
            8  => [],
            9  => [],
            10 => [],
            11 => [],
            12 => []
        ];
        $this->resourcesNeededToAutomate = [
            1  => [2 => 50],
            2  => [1 => 100, 2 => 50, 3 => 10],
            3  => [1 => 10, 2 => 5],
            4  => [],
            5  => [],
            6  => [],
            7  => [],
            8  => [],
            9  => [],
            10 => [],
            11 => [],
            12 => []
        ];

        $this->resourcesNeededToImprove = [
            1  => [1 => 5],
            2  => [2 => 10],
            3  => [3 => 7],
            4  => [],
            5  => [],
            6  => [],
            7  => [],
            8  => [],
            9  => [],
            10 => [],
            11 => [],
            12 => []
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
    }


    private function setEnable()
    {
        foreach ($this->resourcesNeededToEnable as $resouceId => $data) {
            if ( ! $this->enabled[$resouceId]) {
                $canEnable = $this->checkForResources($data);
                $this->updateEligiblity($resouceId, 'enable', $canEnable);
            }
        }
    }


    private function setAutomate()
    {
        foreach ($this->resourcesNeededToAutomate as $resouceId => $data) {
            if ( $this->enabled[$resouceId] && ! $this->automated[$resouceId]) {
                $canAutomate = $this->checkForResources($data);
                $this->updateEligiblity($resouceId, 'automate', $canAutomate);
            }
        }
    }

    private function setImprove()
    {
        foreach ($this->resourcesNeededToImprove as $resouceId => $data) {
            if ( $this->enabled[$resouceId]) {
                $canImprove = $this->checkForResources($data);
                $this->updateEligiblity($resouceId, 'improve', $canImprove);
            }
        }
    }


    /**
     * @param $resourcesNeeded
     *
     * @return bool
     */
    private function checkForResources($resourcesNeeded): bool
    {
        $allow = true;
        foreach ($resourcesNeeded as $neededId => $amountNeeded) {
            if ($allow === true) {
                $allow = ($this->totals[$neededId] >= $amountNeeded);
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
                if ($this->eligibleToEnable[$resouceId] !== $bool) {
                    $this->eligibleToEnable[$resouceId] = $bool;
                    $this->emit('canBeEnabled', $resouceId, $bool);
                }
                break;
            case 'automate' :
                if ($this->eligibleToAutomate[$resouceId] !== $bool) {
                    $this->eligibleToAutomate[$resouceId] = $bool;
                    $this->emit('canBeAutomated', $resouceId, $bool);
                }
                break;
            default :
                if ($this->eligibleToImprove[$resouceId] !== $bool) {
                    $this->eligibleToImprove[$resouceId] = $bool;
                    $this->emit('canBeImproved', $resouceId, $bool);
                }
                break;
        }
    }

    /**
     * note: add an amount to a resource and check if that allows other resources to be enabled
     *
     * @param $id
     * @param $totalToAdd
     */
    public function addToTotal($id, $totalToAdd)
    {
        $this->totals[$id] += $totalToAdd;
        $this->setEnable();
        $this->setAutomate();
        $this->setImprove();
    }


    public function enable($id)
    {
        if($this->eligibleToEnable[$id]) {
            foreach ($this->resourcesNeededToEnable[$id] as $rId => $rAmount) {
                $this->totals[$rId] -= $rAmount;
            }
            $this->enabled[$id] = true;
            $this->setEnable();
        }
    }

    public function automate($id)
    {
        if($this->eligibleToAutomate[$id]) {
            foreach ($this->resourcesNeededToAutomate[$id] as $rId => $rAmount) {
                $this->totals[$rId] -= $rAmount;
            }
            $this->automated[$id] = true;
            $this->setAutomate();
        }
    }

    public function improve($id)
    {
        if($this->eligibleToImprove[$id]) {
            foreach ($this->resourcesNeededToImprove[$id] as $rId => $rAmount) {
                $this->totals[$rId] -= $rAmount;
            }
            $this->setImprove();
        }
    }


    public function render()
    {
        return view('livewire.total-resources');
    }
}
