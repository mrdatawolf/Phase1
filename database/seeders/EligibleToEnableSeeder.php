<?php

namespace Database\Seeders;

use App\Models\EligibleToEnable;
use Illuminate\Database\Seeder;

class EligibleToEnableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<=12;$i++) {
            $re = new EligibleToEnable();
            $re->user_id = 1;
            $re->resource_id = $i;
            $re->status = false;
            $re->save();
        }
    }
}
