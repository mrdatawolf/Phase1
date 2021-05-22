<?php

namespace App\Http\Controllers;

use App\Models\EligibleToAddForeman;
use Illuminate\Http\Request;

class EligibleToAddForemanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return EligibleToAddForeman::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return EligibleToAddForeman::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return EligibleToAddForeman::find($id);
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
        $total = EligibleToAddForeman::find($id);
        $total->update($request->all());

        return $total;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $total = EligibleToAddForeman::find($id);
        return $total->delete();
    }
}
