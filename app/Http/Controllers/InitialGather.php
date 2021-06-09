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
        $return = [
            'resources' => [
                1 => [
                    'ID'            => 1,
                    'Name'          => "Stone",
                    'Amount'        => 1,
                    'GatherRate'    => 2,
                    'Workers'       => 2,
                    'Tools'         => 2,
                    'Foremen'       => 2,
                    'Automated'     => false,
                    'CanAutomate'   => false,
                    'Enabled'       => true,
                    'CanEnable'     => true,
                    'CanAddWorker'  => false,
                    'CanAddTool'    => false,
                    'CanAddForeman' => false
                ],
                2 => [
                    'ID'            => 2,
                    'Name'          => "Water",
                    'Amount'        => 2,
                    'GatherRate'    => 4,
                    'Workers'       => 2,
                    'Tools'         => 2,
                    'Foremen'       => 0,
                    'Automated'     => true,
                    'CanAutomate'   => true,
                    'Enabled'       => false,
                    'CanEnable'     => false,
                    'CanAddWorker'  => false,
                    'CanAddTool'    => false,
                    'CanAddForeman' => false
                ],
                3 => [
                    'ID'            => 3,
                    'Name'          => "Iron",
                    'Amount'        => 0,
                    'GatherRate'    => 0,
                    'Workers'       => 0,
                    'Tools'         => 0,
                    'Foremen'       => 0,
                    'Automated'     => false,
                    'CanAutomate'   => false,
                    'Enabled'       => true,
                    'CanEnable'     => true,
                    'CanAddWorker'  => false,
                    'CanAddTool'    => false,
                    'CanAddForeman' => false
                ],
            ]
        ];

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
        return Resource::find($id);
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
