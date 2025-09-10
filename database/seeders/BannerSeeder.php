<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Ejecutar el seeder.
     */
    public function run(): void
    {
        $banners = [
            [
                'titulo' => 'Bienvenido a nuestra plataforma',
                'descripcion' => 'Descubre todas las funcionalidades y servicios que tenemos para ofrecerte.',
                'imagen' => 'assets/banners/banner1.jpg',
                'texto_boton' => 'Conoce más',
                'enlace_boton' => '/servicios',
                'orden' => 1,
                'activo' => true,
            ],
            [
                'titulo' => 'Nuevos productos disponibles',
                'descripcion' => 'Hemos agregado nuevos productos a nuestro catálogo. ¡Visítalo ahora!',
                'imagen' => 'assets/banners/banner2.jpg',
                'texto_boton' => 'Ver catálogo',
                'enlace_boton' => '/productos',
                'orden' => 2,
                'activo' => true,
            ],
            [
                'titulo' => 'Ofertas especiales',
                'descripcion' => 'Aprovecha nuestras ofertas por tiempo limitado con descuentos increíbles.',
                'imagen' => 'assets/banners/banner3.jpg',
                'texto_boton' => 'Ver ofertas',
                'enlace_boton' => '/ofertas',
                'orden' => 3,
                'activo' => false,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
} 