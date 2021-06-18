<?php namespace App\Http\Traits;

use App\Models\EligibleToAddForeman;
use App\Models\EligibleToAddTool;
use App\Models\EligibleToAddWorker;
use App\Models\EligibleToAutomate;
use App\Models\EligibleToEnable;

trait Status
{
    private function updateStatus($id)
    {
        $this->setStatus('canBeEnabled', $id);
        $this->setStatus('canBeAutomated', $id);
        $this->setStatus('worker', $id);
        $this->setStatus('tool', $id);
        $this->setStatus('foreman', $id);
    }


    private function updateAllStatus()
    {
        for ($i = 1; $i <= 12; $i++) {
            $this->updateStatus($i);
        }
    }

    private function setStatus($type, $id)
    {
        switch ($type) {
            case 'worker' :
                if ($this->enabled[$id]) {
                    $canAdd = ($this->totals[$id] >= $this->resourcesNeededToAddWorker[$id]);
                    $this->updateEligiblity($id, $type, $canAdd);
                }
                break;
            case 'tool' :
                if ($this->enabled[$id]) {
                    $canAdd = ($this->totals[$id] >= $this->resourcesNeededToAddTool[$id]);
                    $this->updateEligiblity($id, $type, $canAdd);
                }
                break;
            case 'foreman' :
                if ($this->enabled[$id]) {
                    $canAdd = ($this->totals[$id] >= $this->resourcesNeededToAddForeman[$id]);
                    $this->updateEligiblity($id, $type, $canAdd);
                }
                break;
            case 'canBeEnabled' :
                if ( ! $this->enabled[$id]) {
                    $data      = $this->resourcesNeededToEnable[$id];
                    $canEnable = $this->hasResourcesNeeded($data);

                    $this->updateEligiblity($id, 'enable', $canEnable);
                }
                break;
            case 'canBeAutomated' :
                if ($this->enabled[$id] && ! $this->automated[$id]) {
                    $data        = $this->resourcesNeededToAutomate[$id];
                    $canAutomate = $this->hasResourcesNeeded($data);

                    $this->updateEligiblity($id, 'automate', $canAutomate);
                }
                break;
            case 'automate' :
                if ($this->eligibleToAutomate[$id]) {
                    $this->automated[$id] = true;
                    $this->emit('toggleAutomate', $id, true);
                }
                break;
            case 'enable' :
                if ($this->eligibleToEnable[$id]) {
                    $this->enabled[$id] = true;
                    $this->emit('toggleEnable', $id, true);
                }
                break;
        }
    }

    /**
     * @param $resourceId
     * @param $type
     * @param $bool
     */
    private function updateEligiblity($resourceId, $type, $bool)
    {
        switch ($type) {
            case 'enable':
                if ( ! $this->enabled[$resourceId]) {
                    $this->eligibleToEnable[$resourceId] = $bool;
                    $ete                                 = EligibleToEnable::where([
                        'user_id'     => auth()->id(),
                        'resource_id' => $resourceId
                    ])->first();
                    $ete->status                         = $bool;
                    $ete->save();
                    $this->emit('canBeEnabled', $resourceId, $bool);
                }
                break;
            case 'automate' :
                if ( ! $this->automated[$resourceId]) {
                    $this->eligibleToAutomate[$resourceId] = $bool;
                    $eta                                   = EligibleToAutomate::where([
                        'user_id'     => auth()->id(),
                        'resource_id' => $resourceId
                    ])->first();
                    $eta->status                           = $bool;
                    $eta->save();
                    $this->emit('canBeAutomated', $resourceId, $bool, $this->resourcesNeededToAutomate[$resourceId]);
                }
                break;
            case 'worker' :
                $this->eligibleToAddWorker[$resourceId] = $bool;
                $etw                                    = EligibleToAddWorker::where([
                    'user_id'     => auth()->id(),
                    'resource_id' => $resourceId
                ])->first();
                $etw->status                            = $bool;
                $etw->save();
                $this->emit('canAddWorker', $resourceId, $bool, $this->resourcesNeededToAddWorker[$resourceId]);
                break;
            case 'tool' :
                $ett         = EligibleToAddTool::where([
                    'user_id'     => auth()->id(),
                    'resource_id' => $resourceId
                ])->first();
                $ett->status = $bool;
                $ett->save();
                $this->eligibleToAddTool[$resourceId] = $bool;
                $this->emit('canAddTool', $resourceId, $bool, $this->resourcesNeededToAddTool[$resourceId]);
                break;
            case 'foreman' :
                $this->eligibleToAddForeman[$resourceId] = $bool;
                $etf                                     = EligibleToAddForeman::where([
                    'user_id'     => auth()->id(),
                    'resource_id' => $resourceId
                ])->first();
                $etf->status                             = $bool;
                $etf->save();
                $this->emit('canAddForeman', $resourceId, $bool, $this->resourcesNeededToAddForeman[$resourceId]);
                break;
        }
    }
}
