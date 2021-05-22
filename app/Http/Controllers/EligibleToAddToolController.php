<?php

namespace App\Http\Controllers;

use App\Models\EligibleToAddTool;
use Illuminate\Http\Request;

class EligibleToAddToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return EligibleToAddTool::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return EligibleToAddTool::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return EligibleToAddTool::find($id);
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
        $total = EligibleToAddTool::find($id);
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
        $total = EligibleToAddTool::find($id);
        return $total->delete();
    }
}
