<?php namespace Database\Factories;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Worker::class;

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
