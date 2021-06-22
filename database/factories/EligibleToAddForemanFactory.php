<?php namespace Database\Factories;

use App\Models\EligibleToAddForeman;
use Illuminate\Database\Eloquent\Factories\Factory;

class EligibleToAddForemanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EligibleToAddForeman::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'resource_id' => 1,
            'status' => false,
        ];
    }
}
