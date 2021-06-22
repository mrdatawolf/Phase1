<?php namespace Database\Factories;

use App\Models\Enable;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Enable::class;

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
