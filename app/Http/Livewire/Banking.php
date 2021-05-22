<?php namespace App\Http\Livewire;

use App\Models\Bank;
use App\Models\ExchangeRate;
use App\Models\TotalResources;
use Livewire\Component;

class Banking extends Component
{
    public $bankBalance = 0;

    protected $listeners = ['sellRequest'];

    public function mount() {
        $this->bankBalance = $this->getBankBalance();
    }


    public function getBankBalance() {
        return Bank::firstOrCreate(['user_id' => auth()->id()])->amount;
    }


    public function sellRequest($userId, $resourceId) {
        $ex = ExchangeRate::firstOrCreate(['user_id' => $userId,'resource_id' => $resourceId]);
        $tr = TotalResources::where(['user_id' => auth()->id()])->first();
        $b  = Bank::where(['user_id' => auth()->id()])->first();
        $goldValue = $tr->amount * $ex->amount;
        $tr->amount = 0;
        $tr->save();
        $b->amount += $goldValue;
        $b->save();
        $ex->amount = ceil($ex->amount*.9);
        $ex->save();
        $this->bankBalance = $b->amount;
        $this->emit('bankDeposit', $userId, $resourceId, $b->amount, $ex->amount);
    }


    public function render()
    {
        return view('livewire.banking');
    }
}
