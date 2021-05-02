<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AutomateResources;

class AddAutomateResourcesNeeded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $resourcesNeededToAutomate = [
            1  => [2 => 50, 3 => 10],
            2  => [1 => 100, 3 => 10],
            3  => [1 => 10, 2 => 5],
            4  => [2 => 500, 3 => 100],
            5  => [1 => 2000, 2 => 2000, 3 => 500, 4 => 4000],
            6  => [1 => 200, 2 => 400],
            7  => [1 => 4000, 2 => 3000],
            8  => [1 => 6000, 2 => 5000],
            9  => [1 => 12000, 2 => 7500],
            10 => [1 => 50000, 2 => 12000],
            11 => [1 => 75000, 2 => 30000],
            12 => [1 => 200000, 2 => 70000]
        ];
        foreach( $resourcesNeededToAutomate as $resourceId => $data) {
            $resource       = new AutomateResources();
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
