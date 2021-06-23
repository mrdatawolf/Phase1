<?php namespace App\Http\Controllers;

use App\Objects\Automate;
use App\Objects\Enable;
use App\Objects\Foreman;
use App\Objects\Gather;
use App\Objects\Tool;
use App\Objects\Worker;
use Illuminate\Http\Request;

class AddController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return null
     */
    public function index()
    {
        return null;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return response()->json('Denied', 403);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return response()->json('Denied', 403);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return response()->json('Denied', 403);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return response()->json('Denied', 403);
    }


    /**
     * @param $type
     * @param $resourceId
     *
     * @return bool
     */
    public function addImprovement($type, $resourceId): bool
    {
        $return = false;
        switch ($type) {
            case 'worker':
                $worker = new Worker($resourceId);
                $return = $worker->add();
                break;
            case 'tool':
                $tool   = new Tool($resourceId);
                $return = $tool->add();
                break;
            case 'foreman':
                $foreman = new Foreman($resourceId);
                $return  = $foreman->add();
                break;
            case 'automate':
                $automate = new Automate($resourceId);
                $return   = $automate->activate();
                break;
            case 'enable':
                $enable = new Enable($resourceId);
                $return = $enable->activate();
                break;
            case 'gather':
                $gather = new Gather($resourceId);
                $return = $gather->add();
        }

        return $return;
    }
}
