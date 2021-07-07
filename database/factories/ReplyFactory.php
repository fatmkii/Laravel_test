<?php

namespace Database\Factories;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reply::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'forum_id' => $this->faker->randomElement(array('1', '12', '40')),
            'thread_id' => $this->faker->randomNumber($nbDigits = 5),
            'floor' => $this->faker->randomNumber($nbDigits = 3),
            'random_head' => $this->faker->randomDigit(),
            'content' => $this->faker->text(200),
        ];
    }
}