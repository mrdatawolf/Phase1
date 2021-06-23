<?php namespace App\Http\Traits;

use App\Models\Automate;
use App\Models\AutomateResources;
use App\Models\EligibleToAddForeman;
use App\Models\EligibleToAddTool;
use App\Models\EligibleToAddWorker;
use App\Models\EligibleToAutomate;
use App\Models\EligibleToEnable;
use App\Models\Enable;
use App\Models\EnableResources;
use App\Models\Foreman;
use App\Models\Gather;
use App\Models\Resource;
use App\Models\ResourceAutomated;
use App\Models\ResourceEnabled;
use App\Models\Tool;
use App\Models\TotalResources;
use App\Models\Worker;

trait Status
{


    private function setStatus($type, $resourceId): bool
    {
        $return = false;
        $workers  = new Worker($resourceId);
        $tools    = new Tool($resourceId);
        $foremen  = new Foreman($resourceId);
        $automate = new Automate($resourceId);
        $enable   = new Enable($resourceId);
        $gather   = new Gather($resourceId);
        $tr = TotalResources::where(['user_id' => $userId, 'resource_id' => $resourceId])->first();
        $re = ResourceEnabled::where(['user_id' => $userId, 'resource_id' => $resourceId])->first();
        $ra = ResourceAutomated::where(['user_id' => $userId, 'resource_id' => $resourceId])->first();
        $ete = EligibleToEnable::where(['user_id' => $userId, 'resource_id' => $resourceId])->first();
        $eta = EligibleToAutomate::where(['user_id' => $userId, 'resource_id' => $resourceId])->first();
        $enabled = ($re->status == 1);
        $automated = ($ra->status == 1);
        $eligibleToAutomate = ($eta->status == 1);
        $eligibleToEnable = ($ete->status == 1);
        switch ($type) {
            case 'worker' :
                if ($enabled) {
                    $canAdd = ($tr->amount >= $this->resourcesNeededToAddWorker[$resourceId]);
                    $this->updateEligiblity($resourceId, $type, $canAdd);
                }
                break;
            case 'tool' :
                if ($enabled) {
                    $canAdd = ($tr->amount >= $this->resourcesNeededToAddTool[$resourceId]);
                    $this->updateEligiblity($resourceId, $type, $canAdd);
                }
                break;
            case 'foreman' :
                if ($enabled) {
                    $canAdd = ($tr->amount >= $this->resourcesNeededToAddForeman[$resourceId]);
                    $this->updateEligiblity($resourceId, $type, $canAdd);
                }
                break;
            case 'canBeEnabled' :
                if ( ! $enabled) {
                    $canEnable = $this->hasResourcesNeeded($type, $resourceId);
                    $this->updateEligiblity($resourceId, 'enable', $canEnable);
                }
                break;
            case 'canBeAutomated' :
                if ($enabled && ! $automated) {
                    $canAutomate = $this->hasResourcesNeeded($type, $resourceId);
                    $this->updateEligiblity($resourceId, 'automate', $canAutomate);
                }
                break;
            case 'automate' :
                if ($enabled && ! $automated && $eligibleToAutomate) {
                    $ra->status = 1;
                    $return = true;
                    //$this->emit('toggleAutomate', $resourceId, true);
                }
                break;
            case 'enable' :
                if (! $enabled && $eligibleToEnable) {
                    $re->status = 1;
                    $return = true;
                    //$this->emit('toggleEnable', $resourceId, true);
                }
                break;
        }

        return $return;
    }

    /**
     * @param $resourceId
     * @param $type
     * @param $bool
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
        $userId = auth()->id();
        switch($type) {
            case 'canBeEnabled' :
            case 'enable' :
                $resourcesNeeded     = EnableResources::where('resource_id', $resourceId)->first();
                break;
            case 'canBeAutomated' :
            case 'automate' :
                $resourcesNeeded     = AutomateResources::where('resource_id', $resourceId)->first();
                break;
                default :
                    dd($type);
        }
        $resources = Resource::all();
        for ($x = 1; $x <= $resources->count(); $x++) {
            $tr = TotalResources::where(['user_id' => $userId, 'resource_id' => $x])->first();
            $thisId = 'r'.$x;
            $amountNeeded = (int) $resourcesNeeded->$thisId;
            $allow = ($tr->amount >= $amountNeeded);
            if ($allow === false) {
                return false;
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
