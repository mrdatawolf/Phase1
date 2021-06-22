<?php namespace Database\Factories;

use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;

class ToolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tool::class;

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
            'value' => 1,
            'amount' => 100,
            'resourceId' => 1,
            'eligibleToAdd' => true,
        ];
    }
}
