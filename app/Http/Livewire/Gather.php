<?php namespace App\Http\Livewire;

use Livewire\Component;

class Gather extends Component
{
    public $resourceId;
    public $resourceName;
    public $gatherAmount    = 1;
    public $canEnable       = false;
    public $canAutomate     = false;
    public $canImprove      = false;
    public $enabled         = false;
    public $allowed         = false;
    public $automated       = false;

    public $listeners       = ['canBeEnabled', 'canBeAutomated', 'canBeImproved'];

    public function allow() {
        $this->allowed = true;
    }

    public function canBeEnabled($id, $bool) {
        if($this->resourceId === $id) {
            $this->canEnable = $bool;
        }
    }

    public function canBeAutomated($id, $bool) {
        if($this->resourceId === $id) {
            $this->canAutomate = $bool;
        }
    }


    public function canBeImproved($id, $bool) {
        if($this->resourceId === $id) {
            $this->canImprove = $bool;
        }
    }

    public function gather() {
        if($this->enabled) {
            $this->emit('addToTotal', $this->resourceId, $this->gatherAmount);
        }
    }

    public function improve() {
        if($this->enabled && $this->canImprove) {
            $this->gatherAmount++;
            $this->emit('improve', $this->resourceId);
        }
        $this->canImprove = false;
    }

    public function enable() {
        if($this->canEnable) {
            $this->enabled = true;
            $this->emit('enable', $this->resourceId);
        }
        $this->canEnable = false;
    }

    public function automate() {
        if($this->canAutomate) {
            $this->automated = true;
            $this->emit('automate', $this->resourceId);
        }
        $this->canAutomate = false;
    }

    public function render()
    {
        return view('livewire.gather');
    }
}
