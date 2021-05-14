<?php

namespace App\Http\Controllers;

use App\Models\EnableResources;
use Illuminate\Http\Request;

class EnableResourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return EnableResources::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return EnableResources::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return EnableResources::find($id);
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
        $total = EnableResources::find($id);
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
        $total = EnableResources::find($id);
        return $total->delete();
    }
}
