<?php namespace Database\Factories;

use App\Models\Resource;
use App\Models\TotalResources;
use Illuminate\Database\Eloquent\Factories\Factory;

class TotalResourcesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TotalResources::class;

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
            'amount' => 100
        ];
    }
}
