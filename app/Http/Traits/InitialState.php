<?php namespace App\Http\Traits;

use App\Models\ResourceAutomated;

trait InitialState
{
        public function isAutomated($id, $resourceId): bool
    {
        return (bool)ResourceAutomated::firstOrCreate([
            'user_id'     => $id,
            'resource_id' => $resourceId
        ])->status;
    }

    public function getAutomationStatus($resourceId) {
        $ra = ResourceAutomated::where(['user_id' => auth()->id(), 'resource_id' => $resourceId])->first();
        $this->automated = $ra->status;
    }
}
