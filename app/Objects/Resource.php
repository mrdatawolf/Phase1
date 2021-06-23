<?php namespace App\Objects;

use App\Http\Traits\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Resource as ResourceModel;

class Resource
{
    use HasFactory;
    use Status;

    private $owner;
    private $resourceId;
    private $amount;
    private $automated;
    private $activated;
    private $eligibleToActivate;
    private $eligibleToAutomate;
    private $eligibleToAddWorker;
    private $eligibleToAddTool;
    private $eligibleToAddForeman;
    private $activateCost;
    private $automateCost;
    private $workerCost;
    private $toolCost;
    private $foremanCost;
    private $workerAmount;
    private $toolAmount;
    private $foremanAmount;
    private $improveMultiplier;
    private $incrementAmount;


    public function __construct($resourceId)
    {
        $this->setResourceId($resourceId);
        $this->setOwner(auth()->id());
        $resourceModel = ResourceModel::where(['id' => $this->getResourceId()])
                                      ->first();
        $worker        = new Worker($resourceId);
        $tool          = new Tool($resourceId);
        $foreman       = new Foreman($resourceId);
        $enable        = new Enable($resourceId);
        $automate      = new Automate($resourceId);

        $this->setAmount($resourceModel->amount($this->getOwner()));
        $this->setAutomated($resourceModel->isAutomated($this->getOwner()));
        $this->setActivated($resourceModel->isEnabled($this->getOwner()));
        $this->setEligibleToActivate($resourceModel->canEnable($this->getOwner()));
        $this->setEligibleToAutomate($resourceModel->canAutomate($this->getOwner()));
        $this->setActivateCost($enable->getCost());
        $this->setAutomateCost($automate->getCost());
        $this->setWorkerCost($worker->getCost());
        $this->setToolCost($tool->getCost());
        $this->setForemanCost($foreman->getCost());
        $this->setWorkerAmount($worker->getAmount());
        $this->setToolAmount($tool->getAmount());
        $this->setForemanAmount($foreman->getAmount());
        $this->setImproveMultiplier($resourceModel->improveModifier($this->getOwner()));
        $this->setIncrementAmount($resourceModel->resourceIncrementAmount($this->getOwner()));
        $this->setEligibleToAddWorker($worker->getEligibleToAdd());
        $this->setEligibleToAddTool($tool->getEligibleToAdd());
        $this->setEligibleToAddForeman($foreman->getEligibleToAdd());
    }


    /**
     * @return mixed
     */
    public function getEligibleToAddWorker()
    {
        return $this->eligibleToAddWorker;
    }


    /**
     * @param mixed $eligibleToAddWorker
     */
    public function setEligibleToAddWorker($eligibleToAddWorker): void
    {
        $this->eligibleToAddWorker = $eligibleToAddWorker;
    }


    /**
     * @return mixed
     */
    public function getEligibleToAddTool()
    {
        return $this->eligibleToAddTool;
    }


    /**
     * @param mixed $eligibleToAddTool
     */
    public function setEligibleToAddTool($eligibleToAddTool): void
    {
        $this->eligibleToAddTool = $eligibleToAddTool;
    }


    /**
     * @return mixed
     */
    public function getEligibleToAddForeman()
    {
        return $this->eligibleToAddForeman;
    }


    /**
     * @param mixed $eligibleToAddForeman
     */
    public function setEligibleToAddForeman($eligibleToAddForeman): void
    {
        $this->eligibleToAddForeman = $eligibleToAddForeman;
    }


    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }


    /**
     * @param mixed $owner
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }


    /**
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }


    /**
     * @param mixed $resourceId
     */
    public function setResourceId($resourceId): void
    {
        $this->resourceId = $resourceId;
    }


    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }


    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }


    /**
     * @return mixed
     */
    public function getAutomated()
    {
        return $this->automated;
    }


    /**
     * @param mixed $automated
     */
    public function setAutomated($automated): void
    {
        $this->automated = $automated;
    }


    /**
     * @return mixed
     */
    public function getActivated()
    {
        return $this->activated;
    }


    /**
     * @param mixed $activated
     */
    public function setActivated($activated): void
    {
        $this->activated = $activated;
    }


    /**
     * @return mixed
     */
    public function getEligibleToActivate()
    {
        return $this->eligibleToActivate;
    }


    /**
     * @param mixed $eligibleToActivate
     */
    public function setEligibleToActivate($eligibleToActivate): void
    {
        $this->eligibleToActivate = $eligibleToActivate;
    }


    /**
     * @return mixed
     */
    public function getEligibleToAutomate()
    {
        return $this->eligibleToAutomate;
    }


    /**
     * @param mixed $eligibleToAutomate
     */
    public function setEligibleToAutomate($eligibleToAutomate): void
    {
        $this->eligibleToAutomate = $eligibleToAutomate;
    }


    /**
     * @return mixed
     */
    public function getActivateCost()
    {
        return $this->activateCost;
    }


    /**
     * @param mixed $activateCost
     */
    public function setActivateCost($activateCost): void
    {
        $this->activateCost = $activateCost;
    }


    /**
     * @return mixed
     */
    public function getAutomateCost()
    {
        return $this->automateCost;
    }


    /**
     * @param mixed $automateCost
     */
    public function setAutomateCost($automateCost): void
    {
        $this->automateCost = $automateCost;
    }


    /**
     * @return mixed
     */
    public function getWorkerCost()
    {
        return $this->workerCost;
    }


    /**
     * @param mixed $workerCost
     */
    public function setWorkerCost($workerCost): void
    {
        $this->workerCost = $workerCost;
    }


    /**
     * @return mixed
     */
    public function getToolCost()
    {
        return $this->toolCost;
    }


    /**
     * @param mixed $toolCost
     */
    public function setToolCost($toolCost): void
    {
        $this->toolCost = $toolCost;
    }


    /**
     * @return mixed
     */
    public function getForemanCost()
    {
        return $this->foremanCost;
    }


    /**
     * @param mixed $foremanCost
     */
    public function setForemanCost($foremanCost): void
    {
        $this->foremanCost = $foremanCost;
    }


    /**
     * @return mixed
     */
    public function getWorkerAmount()
    {
        return $this->workerAmount;
    }


    /**
     * @param mixed $workerAmount
     */
    public function setWorkerAmount($workerAmount): void
    {
        $this->workerAmount = $workerAmount;
    }


    /**
     * @return mixed
     */
    public function getToolAmount()
    {
        return $this->toolAmount;
    }


    /**
     * @param mixed $toolAmount
     */
    public function setToolAmount($toolAmount): void
    {
        $this->toolAmount = $toolAmount;
    }


    /**
     * @return mixed
     */
    public function getForemanAmount()
    {
        return $this->foremanAmount;
    }


    /**
     * @param mixed $foremanAmount
     */
    public function setForemanAmount($foremanAmount): void
    {
        $this->foremanAmount = $foremanAmount;
    }


    /**
     * @return mixed
     */
    public function getImproveMultiplier()
    {
        return $this->improveMultiplier;
    }


    /**
     * @param mixed $improveMultiplier
     */
    public function setImproveMultiplier($improveMultiplier): void
    {
        $this->improveMultiplier = $improveMultiplier;
    }


    /**
     * @return mixed
     */
    public function getIncrementAmount()
    {
        return $this->incrementAmount;
    }


    /**
     * @param mixed $incrementAmount
     */
    public function setIncrementAmount($incrementAmount): void
    {
        $this->incrementAmount = $incrementAmount;
    }

}
