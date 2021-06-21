<?php namespace App\Http\Controllers;

use App\Models\Automate;
use App\Models\Enable;
use App\Models\Foreman;
use App\Models\Gather;
use App\Models\Tool;
use App\Models\Worker;
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd('store', $request->all());
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd('show', $id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd('update', $request->all(), $id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy($id)
    {
        return false;
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
