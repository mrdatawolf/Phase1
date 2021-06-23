<?php namespace App\Http\Traits;

use App\Models\EligibleToAddForeman;
use App\Models\EligibleToAddTool;
use App\Models\EligibleToAddWorker;
use App\Models\EligibleToAutomate;
use App\Models\EligibleToEnable;
use App\Objects\Automate;
use App\Objects\Enable;
use App\Models\Resource;
use App\Models\ResourceAutomated;
use App\Models\ResourceEnabled;

trait Status
{
    private function setStatus($type, $resourceId): bool
    {
        $return = false;
        $enable   = new Enable($resourceId);
        $automate = new Automate($resourceId);
        $resource = new \App\Objects\Resource($resourceId);

        $totalResource = $resource->getAmount();
        switch ($type) {
            case 'worker' :
                if ($enable->getStatus()) {
                    $canAdd = ($totalResource >= $resource->getWorkerCost());
                    $this->updateEligiblity($resourceId, $type, $canAdd);
                }
                break;
            case 'tool' :
                if ($enable->getStatus()) {
                    $canAdd = ($totalResource >= $resource->getToolCost());
                    $this->updateEligiblity($resourceId, $type, $canAdd);
                }
                break;
            case 'foreman' :
                if ($enable->getStatus()) {
                    $canAdd = ($totalResource >= $resource->getForemanCost());
                    $this->updateEligiblity($resourceId, $type, $canAdd);
                }
                break;
            case 'canBeEnabled' :
                if ( ! $enable->getStatus()) {
                    $canEnable = $this->hasResourcesNeeded($type, $resourceId);
                    $this->updateEligiblity($resourceId, 'enable', $canEnable);
                }
                break;
            case 'canBeAutomated' :
                if ($enable->getStatus() && ! $automate->getStatus()) {
                    $canAutomate = $this->hasResourcesNeeded($type, $resourceId);
                    $this->updateEligiblity($resourceId, 'automate', $canAutomate);
                }
                break;
            case 'automate' :
                if ($enable->getStatus() && ! $automate->getStatus() && $resource->getEligibleToAutomate()) {
                    $ra = ResourceAutomated::where(['user_id' => auth()->id(), 'resource_id' => $resourceId])->first();
                    $ra->status = 1;
                    $ra->save();
                    $automate->setStatus(1);
                    $return = true;
                }
                break;
            case 'enable' :
                if (! $enable->getStatus() && $resource->getEligibleToActivate()) {
                    $re = ResourceEnabled::where(['user_id' => auth()->id(), 'resource_id' => $resourceId])->first();
                    $re->status = 1;
                    $re->save();
                    $enable->setStatus(1);
                    $return = true;
                }
                break;
        }

        return $return;
    }


    /**
     * @param $resourceId
     * @param $type
     * @param $bool
     *
     * @return bool
     */
    private function updateEligiblity($resourceId, $type, $bool): bool
    {
        $return = false;
        $userId = auth()->id();
        $re = ResourceEnabled::where(['user_id' => $userId, 'resource_id' => $resourceId])->first();
        $ra = ResourceAutomated::where(['user_id' => $userId, 'resource_id' => $resourceId])->first();
        $enabled = ($re->status == 1);
        $automated = ($ra->status == 1);
        switch ($type) {
            case 'enable':
                if ( ! $enabled) {
                    $ete                                 = EligibleToEnable::where([
                        'user_id'     => auth()->id(),
                        'resource_id' => $resourceId
                    ])->first();
                    $ete->status                         = $bool;
                    $ete->save();
                    $return = true;
                }
                break;
            case 'automate' :
                if ( ! $automated) {
                    $eta                                   = EligibleToAutomate::where([
                        'user_id'     => auth()->id(),
                        'resource_id' => $resourceId
                    ])->first();
                    $eta->status                           = $bool;
                    $eta->save();
                    $return = true;
                }
                break;
            case 'worker' :
                $etw                                    = EligibleToAddWorker::where([
                    'user_id'     => auth()->id(),
                    'resource_id' => $resourceId
                ])->first();
                $etw->status                            = $bool;
                $etw->save();
                $return = true;
                break;
            case 'tool' :
                $ett         = EligibleToAddTool::where([
                    'user_id'     => auth()->id(),
                    'resource_id' => $resourceId
                ])->first();
                $ett->status = $bool;
                $ett->save();
                $return = true;
                break;
            case 'foreman' :
                $etf                                     = EligibleToAddForeman::where([
                    'user_id'     => auth()->id(),
                    'resource_id' => $resourceId
                ])->first();
                $etf->status                             = $bool;
                $etf->save();
                $return = true;

                break;
        }

        return $return;
    }


    /**
     * @param $type
     * @param $resourceId
     *
     * @return bool
     */
    private function hasResourcesNeeded($type, $resourceId): bool
    {
        $resources = Resource::all();
        $resource = new \App\Objects\Resource($resourceId);
        switch($type) {
            case 'canBeEnabled' :
            case 'enable' :
                $resourcesNeeded     = $resource->getActivateCost();
                break;
            case 'canBeAutomated' :
            case 'automate' :
                $resourcesNeeded     = $resource->getAutomateCost();
                break;
                default :
                   return false;
        }

        for ($x = 1; $x <= $resources->count(); $x++) {
            $r = new \App\Objects\Resource($x);
            if(! empty($resourcesNeeded->$x)) {
                $amountNeeded = (int)$resourcesNeeded->$x;
                if ($r->getAmount() >= $amountNeeded) {
                    return false;
                }
            }
        }

        return true;
    }

    private function updateAllStatus() {
        $resourceCount = Resource::count();
        for ($i = 1; $i <= $resourceCount; $i++) {
            $this->setStatus('canBeEnabled', $i);
            $this->setStatus('canBeAutomated', $i);
            $this->setStatus('worker', $i);
            $this->setStatus('tool', $i);
            $this->setStatus('foreman', $i);
        }
    }
}
