<?php namespace Database\Factories;

use App\Models\EligibleToAddWorker;
use Illuminate\Database\Eloquent\Factories\Factory;

class EligibleToAddWorkerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EligibleToAddWorker::class;

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
