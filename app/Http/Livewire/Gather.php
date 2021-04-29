<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Gather extends Component
{
    public $resourceId;
    public $resourceName;
    public $gatherAmount    = 1;
    public $canEnable       = false;
    public $enabled         = false;
    public $allowed         = false;

    public $listeners       = ['canBeEnabled'];

    public function mount() {
        if($this->enabled) {
            $this->emit('enable', $this->resourceId);
        }
        if($this->allowed) {
            $this->emit('allow', $this->resourceId);
        }
    }

    public function gather() {
        $this->emit('addToTotal', $this->resourceId, $this->gatherAmount);
    }

    public function improveGather() {
        $this->gatherAmount++;
    }

    public function allow() {
        $this->allowed = true;
    }

    public function enable() {
        if($this->canEnable) {
            $this->enabled = true;
            $this->emit('enable', $this->resourceId);
        }
    }

    public function canBeEnabled($id, $bool) {
        if($this->resourceId === $id) {
            $this->canEnable = $bool;
        }
    }

    public function render()
    {
        return view('livewire.gather');
    }
}
