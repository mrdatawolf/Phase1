<?php namespace Database\Factories;

use App\Models\EligibleToEnable;
use Illuminate\Database\Eloquent\Factories\Factory;

class EligibleToEnableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EligibleToEnable::class;

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
