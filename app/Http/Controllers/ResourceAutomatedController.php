<?php

namespace App\Http\Controllers;

use App\Models\ResourceAutomated;
use Illuminate\Http\Request;

class ResourceAutomatedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return ResourceAutomated::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return ResourceAutomated::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ResourceAutomated::find($id);
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
        $total = ResourceAutomated::find($id);
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
        $total = ResourceAutomated::find($id);
        return $total->delete();
    }
}