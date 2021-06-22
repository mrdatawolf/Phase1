<?php

namespace Database\Factories;

use App\Models\Automate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class AutomateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Automate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'cost' => [1 => 1, 2 => 4, 3 => '20'],
            'owner' => 1,
            'resourceId' => 1,
            'eligibleToActivate' => true,
            'status' => false
        ];
    }
}
