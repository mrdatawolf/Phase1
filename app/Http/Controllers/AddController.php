<?php namespace App\Http\Controllers;

use App\Http\Traits\IsEligibleTo;
use App\Http\Traits\PayFor;
use App\Http\Traits\RequestTrait;
use App\Http\Traits\UpdateResourceTotal;
use Illuminate\Http\Request;

class AddController extends Controller
{
    use IsEligibleTo;
    use UpdateResourceTotal;
    use PayFor;
    use RequestTrait;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd('store', $request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd('show', $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd('update', $request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
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
     * @return int|null
     */
    public function addImprovement($type, $resourceId): ?int
    {
        $return = 0;
        switch($type) {
            case 'worker':
            case 'tool':
            case 'foreman':
            $return = $this->requestAdd($resourceId, $type);
                break;
            case 'automate':
                $return = $this->requestAutomate($resourceId);
                break;
            case 'enable':
                $return = $this->requestEnable($resourceId);
                break;
            case 'gather':
                $return = $this->requestGather($resourceId);
        }

        return $return;
    }
}
