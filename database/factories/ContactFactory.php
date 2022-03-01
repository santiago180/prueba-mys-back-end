<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Type;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'      => $this->faker->name(),
            'nit'       => $this->faker->numerify('#-#######'),
            'phone'     => $this->faker->numerify('### ### ####'),
            'type_id'   => $this->faker->randomElement([1, 2]),
        ];
    }
}
