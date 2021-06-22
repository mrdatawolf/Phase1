<?php

namespace Database\Seeders;

use App\Models\ResourceAutomated;
use Illuminate\Database\Seeder;

class ResourceAutomatedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<=12;$i++) {
            $re = new ResourceAutomated();
            $re->user_id = 1;
            $re->resource_id = $i;
            $re->status = false;
            $re->save();
        }
    }
}
