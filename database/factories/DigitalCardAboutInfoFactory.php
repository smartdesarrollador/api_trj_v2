<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DigitalCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DigitalCardAboutInfo>
 */
class DigitalCardAboutInfoFactory extends Factory
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
            'description' => $this->faker->paragraph(3),
            'skills' => [
                'Angular',
                'React',
                'Laravel',
                'JavaScript',
                'TypeScript',
                'PHP',
            ],
            'experience' => $this->faker->numberBetween(1, 20),
        ];
    }
}
