<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use  \App\Models\EnableResources;

class AddEnableResourcesNeeded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $resourcesNeededToEnable   = [
            1  => [],
            2  => [1 => 10],
            3  => [1 => 10, 2 => 5],
            4  => [1 => 5, 2 => 30, 3 => 20],
            5  => [1 => 400, 2 => 100],
            6  => [1 => 500, 2 => 450],
            7  => [1 => 700, 2 => 350],
            8  => [1 => 900, 2 => 750],
            9  => [1 => 100, 2 => 1250],
            10 => [1 => 500, 2 => 1450],
            11 => [1 => 5000, 2 => 4500],
            12 => [1 => 9000, 2 => 4050]
        ];
        foreach( $resourcesNeededToEnable as $resourceId => $data) {
            $resource       = new EnableResources();
            $resource->resource_id = $resourceId;
            foreach ($data as $id => $amount) {
                $thisId ='r'.$id;
                $resource->$thisId = $amount;
            }
            $resource->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
