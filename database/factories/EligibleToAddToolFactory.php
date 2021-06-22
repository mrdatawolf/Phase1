<?php namespace Database\Factories;

use App\Models\EligibleToAddTool;
use Illuminate\Database\Eloquent\Factories\Factory;

class EligibleToAddToolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EligibleToAddTool::class;

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
