<?php namespace App\Http\Controllers;

use App\Models\Enable;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnableController extends Controller
{

    /**
     * Display the status of each resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $return = [];
        $resources = Resource::all();
        foreach($resources as $resource) {
            $enable = new Enable($resource->id);
            $return[$resource->id] = $enable->getStatus();
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
        $enable = new Enable($resourceId);

        return response()->json($enable->getStatus(), 200);
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
        $enable = new Enable($resourceId);

        return response()->json($enable->activate(), 200);
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
        $enable = new Enable($resourceId);

        return response()->json($enable->deactivate(), 200);
    }
}
