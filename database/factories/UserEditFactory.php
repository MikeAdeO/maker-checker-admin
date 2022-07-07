<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\UserDraft;
use App\Models\UserEdit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserEditFactory extends Factory
{


    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = UserEdit::class;
    public function definition()
    {
        $editable = $this->editable();
        return [
            'maker_id' => Admin::where('role', 'maker')->get()->random()->id,
            'request_type' => 'create',
            'editable_id' => $editable::factory(),
            'editable_type' => $editable
        ];
    }

    public function editable()
    {
        return $this->faker->randomElement([
            UserDraft::class
        ]);
    }
}
