<?php

namespace App\Http\Controllers;

use App\Models\ResourceEnabled;
use Illuminate\Http\Request;

class ResourceEnabledController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return ResourceEnabled::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return ResourceEnabled::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ResourceEnabled::find($id);
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
        $total = ResourceEnabled::find($id);
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
        $total = ResourceEnabled::find($id);
        return $total->delete();
    }
}
