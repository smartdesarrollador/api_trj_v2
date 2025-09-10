<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\DigitalCard;
use App\Models\User;
use Illuminate\Database\Seeder;

class DigitalCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario demo con datos exactos del mock
        $user = User::firstOrCreate(
            ['email' => 'sistema5000smart@gmail.com'],
            [
                'name' => 'Jeans Enrique Malón Reyna',
                'password' => bcrypt('Password123$'),
                'rol' => 'autor',
            ]
        );

        // Crear tarjeta digital con datos mock exactos
        $digitalCard = DigitalCard::firstOrCreate(
            ['user_id' => $user->id, 'slug' => 'jeans-malon-reyna'],
            [
                'is_active' => true,
                'is_public' => true,
            ]
        );

        // Información personal
        $digitalCard->personalInfo()->firstOrCreate(
            ['digital_card_id' => $digitalCard->id],
            [
                'name' => 'Jeans Enrique Malón Reyna',
                'title' => 'Desarrollador Full Stack',
                'location' => 'Lima, Perú',
                'photo' => 'assets/foto/perfil.jpg',
            ]
        );

        // Información de contacto
        $digitalCard->contactInfo()->firstOrCreate(
            ['digital_card_id' => $digitalCard->id],
            [
                'email' => 'sistema5000smart@gmail.com',
                'phone' => '+51 955365043',
                'facebook' => 'https://www.facebook.com/jeansenrique.malonreyna',
                'website' => 'https://portafolio.smartdigitaltec.com',
                'whatsapp' => '+51 955365043',
            ]
        );

        // Información acerca de
        $digitalCard->aboutInfo()->firstOrCreate(
            ['digital_card_id' => $digitalCard->id],
            [
                'description' => 'Especializado en desarrollo web moderno con Angular, React, Laravel y diseño centrado en el usuario.',
                'skills' => ['Angular', 'React', 'Laravel', 'JavaScript', 'TypeScript', 'PHP'],
                'experience' => 10,
            ]
        );

        // Crear 10 tarjetas adicionales para pruebas
        DigitalCard::factory(10)
            ->hasPersonalInfo()
            ->hasContactInfo()
            ->hasAboutInfo()
            ->create();
    }
}
