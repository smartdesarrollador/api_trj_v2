<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DigitalCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DigitalCardPersonalInfo>
 */
class DigitalCardPersonalInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'digital_card_id' => DigitalCard::factory(),
            'name' => $this->faker->name(),
            'title' => $this->faker->jobTitle(),
            'location' => $this->faker->city() . ', ' . $this->faker->country(),
            'photo' => 'assets/foto/perfil.jpg',
        ];
    }
}
