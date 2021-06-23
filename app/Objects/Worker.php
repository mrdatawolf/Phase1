<?php namespace App\Objects;

use App\Http\Traits\Status;
use App\Models\EligibleToAddWorker;
use App\Models\TotalResources;
use App\Models\TotalWorkers;

class Worker
{
    private $cost;
    private $value;
    private $amount;
    private $owner;
    private $resourceId;
    private $eligibleToAdd;
    private $baseCost;

    use Status;

    public function __construct($resourceId)
    {
        $this->setResourceId($resourceId);
        $this->setOwner(auth()->id());
        $this->setBaseCost(config('placeholders.worker_base_cost.'.$resourceId));
        $amount = $this->gatherAmount();
        $this->setAmount($amount);
        $this->setCost($this->baseCost * $this->amount);
        $this->setValue(1);
        $eligible = $this->gatherEligibleToAdd();
        $this->setEligibleToAdd($eligible);
    }


    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }


    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }


    /**
     * @return int
     */
    public function getOwner(): int
    {
        return $this->owner;
    }


    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }


    /**
     * @return mixed
     */
    public function getEligibleToAdd()
    {
        return $this->eligibleToAdd;
    }


    /**
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }


    /**
     * @return mixed
     */
    public function getBaseCost()
    {
        return $this->baseCost;
    }


    private function gatherAmount() {
        return TotalWorkers::where(['user_id' => $this->owner, 'resource_id' => $this->resourceId])->first()->amount;
    }


    private function gatherEligibleToAdd(): bool
    {
        return (EligibleToAddWorker::where(['user_id' => $this->owner, 'resource_id' => $this->resourceId])
                                   ->first()->status == 1);
    }


    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }


    /**
     * @param int $cost
     */
    public function setCost(int $cost): void
    {
        $this->cost = $cost;
    }


    /**
     * @param int $owner
     */
    public function setOwner(int $owner): void
    {
        $this->owner = $owner;
    }


    /**
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }


    /**
     * @param bool $eligibleToAdd
     */
    public function setEligibleToAdd(bool $eligibleToAdd): void
    {
        $this->eligibleToAdd = $eligibleToAdd;
    }


    /**
     * @param mixed $resourceId
     */
    public function setResourceId($resourceId): void
    {
        $this->resourceId = $resourceId;
    }


    /**
     * @param mixed $baseCost
     */
    public function setBaseCost($baseCost): void
    {
        $this->baseCost = $baseCost;
    }


    public function add()
    {
        if ($this->eligibleToAdd) {
            $this->payForAddition();
            $total = TotalWorkers::where(['user_id' => $this->owner, 'resource_id' => $this->resourceId])->first();
            $total->amount++;
            $total->save();
            $this->setAmount($total->amount);
            $this->updateAllStatus();

            return $total->amount;
        } else {
            $tr = TotalResources::where(['user_id' => $this->owner, 'resource_id' => $this->resourceId])->first();

            return $tr->amount -= $this->cost;
        }
    }


    private function payForAddition()
    {
        $tr         = TotalResources::where(['user_id' => $this->owner, 'resource_id' => $this->resourceId])->first();
        $tr->amount -= $this->cost;
        $tr->save();
    }
}
