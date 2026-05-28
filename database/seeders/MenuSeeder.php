<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Margherita Pizza',
                'description' => 'Classic pizza with tomato sauce, mozzarella, and basil.',
                'price' => 9.99,
                'is_available' => true,
            ],
            [
                'name' => 'Grilled Chicken Burger',
                'description' => 'Juicy grilled chicken patty with lettuce, tomato, and house sauce.',
                'price' => 8.49,
                'is_available' => true,
            ],
            [
                'name' => 'Caesar Salad',
                'description' => 'Crisp romaine lettuce with Caesar dressing, croutons, and parmesan.',
                'price' => 6.99,
                'is_available' => true,
            ],
            [
                'name' => 'Pasta Alfredo',
                'description' => 'Creamy Alfredo pasta topped with herbs and parmesan cheese.',
                'price' => 10.99,
                'is_available' => true,
            ],
            [
                'name' => 'Chocolate Lava Cake',
                'description' => 'Warm chocolate cake with a gooey center served with vanilla cream.',
                'price' => 5.99,
                'is_available' => true,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['name' => $menu['name']],
                $menu
            );
        }
    }
}
