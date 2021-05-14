<?php

namespace App\Http\Controllers;

use App\Models\EligibleToImprove;
use Illuminate\Http\Request;

class EligibleToImproveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return EligibleToImprove::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return EligibleToImprove::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return EligibleToImprove::find($id);
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
        $total = EligibleToImprove::find($id);
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
        $total = EligibleToImprove::find($id);
        return $total->delete();
    }
}
