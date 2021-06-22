<?php namespace App\Models;

use App\Http\Traits\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gather
{
    use HasFactory;
    use Status;

    private $owner;
    private $resourceId;
    private $status;
    private $amount;
    private $multiplier;


    public function __construct($resourceId)
    {
        $this->setResourceId($resourceId);
        $this->setOwner(auth()->id());
        $status = $this->gatherStatus();
        $this->setStatus($status);
        $amount = $this->gatherAmount();
        $this->setAmount($amount);
        $multiplier = $this->gatherMultiplier();
        $this->setMultiplier($multiplier);
    }


    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }


    /**
     * @param mixed $owner
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }


    /**
     * @param mixed $resourceId
     */
    public function setResourceId($resourceId): void
    {
        $this->resourceId = $resourceId;
    }


    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }


    /**
     * @param mixed $multiplier
     */
    public function setMultiplier($multiplier): void
    {
        $this->multiplier = $multiplier;
    }


    private function gatherAmount()
    {
        $amount = 0;
        if ($this->status) {
            $amount = ResourceIncrementAmounts::where([
                'user_id'     => $this->owner,
                'resource_id' => $this->resourceId
            ])->first()->amount;
        }

        return $amount;
    }


    private function gatherStatus()
    {
        return ResourceEnabled::where([
            'user_id'     => $this->owner,
            'resource_id' => $this->resourceId
        ])->first()->status;
    }


    private function gatherMultiplier()
    {
        $im = ImproveMultiplier::firstOrNew(['user_id' => $this->owner, 'resource_id' => $this->resourceId]);
        if ($im->amount == 0) {
            $initialValues = config('multipliers.gather');
            $im->amount    = $initialValues[$this->resourceId];
            $im->save();
        }

        return $im->amount;
    }


    /**
     * @return mixed
     */
    public function getMultiplier()
    {
        return $this->multiplier;
    }


    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
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
    public function getAmount()
    {
        return $this->amount;
    }


    public function add()
    {
        $amount = 0;
        if ($this->status) {
            $tr         = TotalResources::where(['user_id' => $this->owner, 'resource_id' => $this->resourceId])
                                        ->first();
            $tr->amount += $this->amount * $this->multiplier;
            $tr->save();
            $amount = $tr->amount;
            $this->setAmount($amount);
            $this->updateAllStatus();
        }

        return $amount;
    }

}
