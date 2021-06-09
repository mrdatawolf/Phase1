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
        $resources = Resource::all();
        $return['resources'] = [];
        foreach($resources as $resource) {
            $return['resources'][] = [
                'ID'            => $resource->id,
                'Name'          => $resource->name,
                'Amount'        => $resource->amount(),
                'GatherRate'    => $resource->resourceIncrementAmount(),
                'Workers'       => $resource->totalWorkers(),
                'Tools'         => $resource->totalTools(),
                'Foremen'       => $resource->totalForeman(),
                'Automated'     => $resource->isAutomated(),
                'CanAutomate'   => $resource->canAutomate(),
                'Enabled'       => $resource->isEnabled(),
                'CanEnable'     => $resource->canEnable(),
                'CanAddWorker'  => $resource->canAddWorker(),
                'CanAddTool'    => $resource->canAddTool(),
                'CanAddForeman' => $resource->canAddForeman()
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
    public function show($id)
    {
        $resource = Resource::find($id);
        $return = [
            'ID'            => $resource->id,
            'Name'          => $resource->name,
            'Amount'        => $resource->amount(),
            'GatherRate'    => $resource->resourceIncrementAmount(),
            'Workers'       => $resource->totalWorkers(),
            'Tools'         => $resource->totalTools(),
            'Foremen'       => $resource->totalForeman(),
            'Automated'     => $resource->isAutomated(),
            'CanAutomate'   => $resource->canAutomate(),
            'Enabled'       => $resource->isEnabled(),
            'CanEnable'     => $resource->canEnable(),
            'CanAddWorker'  => $resource->canAddWorker(),
            'CanAddTool'    => $resource->canAddTool(),
            'CanAddForeman' => $resource->canAddForeman()
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
