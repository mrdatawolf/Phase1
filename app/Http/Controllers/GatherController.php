<?php namespace App\Http\Controllers;

use App\Models\Gather;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GatherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $return = [];
        $resources = Resource::all();
        foreach($resources as $resource) {
            $gather = new Gather($resource->id);
            $return[$resource->id] = $gather->getAmount();
        }

        return response()->json($return, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
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
        $gather = new Gather($resourceId);

        return response()->json($gather->getAmount(), 200);
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
        $gather = new Gather($resourceId);

        return response()->json($gather->add(), 200);
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
        return response()->json('Not implemented for ' . $resourceId, 501);
    }
}
