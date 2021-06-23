<?php namespace App\Http\Controllers;

use App\Objects\Automate;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AutomateController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $return = [];
        $resources = Resource::all();
        foreach($resources as $resource) {
            $automate = new Automate($resource->id);
            $return[$resource->id] = $automate->getStatus();
        }

        return response()->json($return, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json('Denied', 403);
    }


    /**
     * Display the specified resource.
     *
     * @param int $resourceId
     *
     * @return JsonResponse
     */
    public function show(int $resourceId): JsonResponse
    {
        $automate = new Automate($resourceId);

        return response()->json($automate->getStatus(), 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param int                       $resourceId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $resourceId): JsonResponse
    {
        $automate = new Automate($resourceId);

        return response()->json($automate->activate(), 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $resourceId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $resourceId): JsonResponse
    {
        $automate = new Automate($resourceId);

        return response()->json($automate->deactivate(), 200);
    }
}
