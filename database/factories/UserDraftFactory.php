<?php

namespace Database\Factories;

use App\Models\UserDraft;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDraftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = UserDraft::class;
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
