<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class InitialGather extends Controller
{
    /**
     * note: this should return all the data needed by a client to pickup the game for a user.
     * @return string
     */
    public function index(): string
    {
        $userId = auth()->user()->id;
        $resources = Resource::all();
        $return['resources'] = [];
        foreach($resources as $resource) {
            $return['resources'][] = [
                'ID'            => $resource->id,
                'Name'          => $resource->name,
                'Amount'        => $resource->amount($userId),
                'GatherRate'    => $resource->resourceIncrementAmount($userId),
                'Workers'       => $resource->totalWorkers($userId),
                'Tools'         => $resource->totalTools($userId),
                'Foremen'       => $resource->totalForeman($userId),
                'Automated'     => $resource->isAutomated($userId),
                'CanAutomate'   => $resource->canAutomate($userId),
                'Enabled'       => $resource->isEnabled($userId),
                'CanEnable'     => $resource->canEnable($userId),
                'CanAddWorker'  => $resource->canAddWorker($userId),
                'CanAddTool'    => $resource->canAddTool($userId),
                'CanAddForeman' => $resource->canAddForeman($userId)
            ];
        }

        return json_encode($return);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return null
     */
    public function store(Request $request)
    {
        return null;
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return null
     */
    public function show(int $id)
    {
        $userId = auth()->user()->id;
        $resource = Resource::find($id);
        $return = [
            'ID'            => $resource->id,
            'Name'          => $resource->name,
            'Amount'        => $resource->amount($userId),
            'GatherRate'    => $resource->resourceIncrementAmount($userId),
            'Workers'       => $resource->totalWorkers($userId),
            'Tools'         => $resource->totalTools($userId),
            'Foremen'       => $resource->totalForeman($userId),
            'Automated'     => $resource->isAutomated($userId),
            'CanAutomate'   => $resource->canAutomate($userId),
            'Enabled'       => $resource->isEnabled($userId),
            'CanEnable'     => $resource->canEnable($userId),
            'CanAddWorker'  => $resource->canAddWorker($userId),
            'CanAddTool'    => $resource->canAddTool($userId),
            'CanAddForeman' => $resource->canAddForeman($userId)
        ];


        return json_encode($return);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return null
     */
    public function update(Request $request, $id)
    {
        return null;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return null
     */
    public function destroy($id)
    {
        return null;
    }
}
