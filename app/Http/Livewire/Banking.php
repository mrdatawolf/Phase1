<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Banking extends Component
{
    public $bankBalance = 0;
    public function render()
    {
        return view('livewire.banking');
    }
}
