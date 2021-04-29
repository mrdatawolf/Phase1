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
     * @var
     * note: this tells us if the user has gathered enough resources to allow activation
     */
    public $eligibleToEnable;
    /**
     * note: resources needed to enable gathering of another resource
     * note2: resource id to enable [ resource needed =>amount of the resouce needed ]
     */
    public $resourcesNeeded;

    public $enabled;

    public $listeners           = ['addToTotal', 'enable'];

    public function mount() {
        $this->totals           = [1 => 9, 2 => 0, 3 => 0];
        $this->resourcesNeeded  = [1 => [], 2 => [1 => 10], 3 => [2 => 5]];
        $this->eligibleToEnable = [1 => false, 2 => false, 3 => false];
        $this->enabled          = [1 => true, 2 => false, 3 => false];
    }

    /**
     * note: add an amount to a resource and check if that allows other resources to be enabled
     * @param $id
     * @param $totalToAdd
     */
    public function addToTotal($id, $totalToAdd) {
        $this->totals[$id] += $totalToAdd;
        $this->checkCanEnable();
    }

    public function checkCanEnable() {
        foreach ($this->resourcesNeeded as $resouceId => $data) {
            if ( ! $this->eligibleToEnable[$resouceId]) {
                $canEnable = true;
                foreach ($data as $neededId => $amountNeeded) {
                    if($canEnable === true) {
                        $canEnable = ($this->totals[$neededId] >= $amountNeeded);
                    }
                }
                if($this->eligibleToEnable[$resouceId] !== $canEnable) {
                    $this->eligibleToEnable[$resouceId] = $canEnable;
                    $this->emit('canBeEnabled', $resouceId, $canEnable);
                }
            }
        }
    }

    public function enable($id) {
        $this->enabled[$id] = true;
    }

    public function render()
    {
        return view('livewire.total-resources');
    }
}
