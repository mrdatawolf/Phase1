<?php

namespace App\Http\Traits;

use App\Models\EligibleToAddForeman;
use App\Models\EligibleToAddTool;
use App\Models\EligibleToAddWorker;
use App\Models\EligibleToAutomate;
use App\Models\EligibleToEnable;

trait IsEligibleTo
{
    public function isEligibleToAddForeman($id, $resourceId): bool
    {
        return (bool)EligibleToAddForeman::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->status;
    }


    public function isEligibleToAddTool($id, $resourceId): bool
    {
        return (bool)EligibleToAddTool::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->status;
    }


    public function isEligibleToAddWorker($id, $resourceId): bool
    {
        return (bool)EligibleToAddWorker::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->status;
    }


    public function isEligibleToAutomate($id, $resourceId): bool
    {
        return (bool)EligibleToAutomate::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->status;
    }


    public function isEligibleToEnable($id, $resourceId): bool
    {
        return (bool)EligibleToEnable::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->status;
    }
}
