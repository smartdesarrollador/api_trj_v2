<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DigitalCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DigitalCardContactInfo>
 */
class DigitalCardContactInfoFactory extends Factory
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
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'linkedin' => $this->faker->optional()->url(),
            'website' => $this->faker->optional()->url(),
            'twitter' => $this->faker->optional()->url(),
            'instagram' => $this->faker->optional()->url(),
            'github' => $this->faker->optional()->url(),
            'youtube' => $this->faker->optional()->url(),
            'tiktok' => $this->faker->optional()->url(),
            'whatsapp' => $this->faker->phoneNumber(),
            'facebook' => $this->faker->optional()->url(),
        ];
    }
}
