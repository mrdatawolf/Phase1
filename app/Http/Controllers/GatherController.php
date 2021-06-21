<?php namespace App\Http\Controllers;

use App\Http\Traits\IsEligibleTo;
use App\Http\Traits\PayFor;
use App\Http\Traits\RequestTrait;
use App\Http\Traits\UpdateResourceTotal;
use App\Models\Gather;
use Illuminate\Http\Request;

class GatherController extends Controller
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
     *
     * @return void
     */
    public function store(Request $request)
    {
        dd('store', $request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $resourceId
     *
     * @return int
     */
    public function show(int $resourceId): int
    {
        return $this->requestGather($resourceId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param int                       $id
     *
     * @return void
     */
    public function update(Request $request, int $id)
    {
        //check if resource can be added to.
        //add the resource
        //update eligiblity
        dd('update', $request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return bool
     */
    public function destroy($id): bool
    {
        return false;
    }

    public function gather($resourceId) {
        $gather = new Gather($resourceId);

        return $gather->add();
    }
}
