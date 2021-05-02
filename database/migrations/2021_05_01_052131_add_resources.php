<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Resource;

class AddResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach(['stone', 'water', 'wood', 'grain', 'livestock', 'clay', 'silver', 'gold', 'copper', 'tin', 'iron', 'aluminum'] as $name) {
            $resource       = new Resource();
            $resource->name = $name;
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
