<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date,
            'time' => $this->faker->time,
            'location' => $this->faker->address,
            'capacity' => $this->faker->numberBetween(10, 200),
            'host_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
