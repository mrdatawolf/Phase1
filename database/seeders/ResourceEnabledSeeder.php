<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ResourceEnabled;

class ResourceEnabledSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<=12;$i++) {
            $re = new ResourceEnabled();
            $re->user_id = 1;
            $re->resource_id = $i;
            $re->status = false;
            $re->save();
        }
    }
}
