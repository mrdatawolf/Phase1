<?php namespace Database\Factories;

use App\Models\ResourceEnabled;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class ResourceEnabledFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResourceEnabled::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = new Faker();
        return [
            'user_id' => $faker->randomNumber(1),
            'resource_id' => $faker->numberBetween(1,12),
            'status' => $faker->boolean
        ];
    }
}
