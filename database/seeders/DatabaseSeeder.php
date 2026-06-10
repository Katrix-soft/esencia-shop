<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Categories
        $amaderado = \App\Models\Category::create([
            'name' => 'Amaderado',
            'slug' => 'amaderado',
            'color_class' => 'bg-secondary-container text-on-secondary-container'
        ]);

        $citrico = \App\Models\Category::create([
            'name' => 'Cítrico Terroso',
            'slug' => 'citrico-terroso',
            'color_class' => 'bg-primary-container text-on-primary-container'
        ]);

        $oriental = \App\Models\Category::create([
            'name' => 'Oriental Especiado',
            'slug' => 'oriental-especiado',
            'color_class' => 'bg-tertiary-container text-on-tertiary-container'
        ]);

        $floral = \App\Models\Category::create([
            'name' => 'Floral Blanco',
            'slug' => 'floral-blanco',
            'color_class' => 'bg-surface-variant text-on-surface-variant'
        ]);

        // Products
        \App\Models\Product::create([
            'category_id' => $amaderado->id,
            'name' => 'Sauvage',
            'description' => 'Sándalo cremoso, cedro de Virginia y un toque de cardamomo terroso.',
            'price' => 35000,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuALCeIr5724Uy4nrTwpBaqkw4YCpL3i1HLda7MZhylrqTsXkunwz3vj8zIG1QX6l4fGAwEnAZcny6hgMhYvKpu4jyVW4YKnkheqK1n9oOGdZLHzY-ZQbQ3jlclrLLND-zdL4r79Twa8kTVXX3oQrrtDZdGL3pTz1qkH_sktQuZzBgPaazhgvARwQUYOC5uHuGe1h_SZ_gHthvLRY13gLyCg19-FpcSIFP2yY3ChqPVDEREwku3NqrEwp4CglbcwnyRo_V3dvjJijxw',
            'wood' => 70,
            'citrus' => 20,
            'floral' => 10,
            'stock' => 5 // Solo 5 en stock para probar
        ]);

        \App\Models\Product::create([
            'category_id' => $citrico->id,
            'name' => 'Aventus',
            'description' => 'Flor de azahar, mandarina verde y almizcle blanco suave.',
            'price' => 28000,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDkd86VqaTTXdLZbhBi6DX0-QkTt7recLHsKpzhyvRu6NDRINeZ78Z5LfjpbWEy77zHGNTtim-InM59yDZOLxUMHGv_P_7Ekk1Lr0d8ClDH0BNvnB2QlIKX30wOQc3OZW7hSl0e5k7xb97Rsjg2WoVRqiLwoh9lFSelhOi0jP3gUPYTIZ2pcLlZ90K9bL5dwGf3mXYItL4ZznxilvdyNLkcYfyWVqGQskFj82dNd4cqyDiSRUkYmZVnPeqQwHMywDf78pFWMINP15o',
            'wood' => 15,
            'citrus' => 70,
            'floral' => 15,
            'stock' => 15
        ]);

        \App\Models\Product::create([
            'category_id' => $oriental->id,
            'name' => 'Baccarat Rouge 540',
            'description' => 'Ámbar gris cálido, vainilla orgánica y resina de ládano.',
            'price' => 42000,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDanohCFoATw9fj8u5l6UX6M-ok5a52c0GsYDZObc7vjXBPiM_dCBa0Z676fqgWOJdLLFKKiYyJ-JIr6vzzpM54dJ-cAbEe7XcltBxmVRhimiXrh0IwhoTJnvhpA_V2Qs5qrWq0AZYBmVJ4otHQ4Hu6Wrz7wZ9amoIJL_C3bUUhWiRB52ev3Wp6hZforkF3NL_D1JAW9qKu2rH2EXHGAK78V3Ve-Wjm0WfF2UbRM8khPFQUwZZvwPgpjruXmx5w8LcbkU9F8ujhmeU',
            'wood' => 30,
            'citrus' => 10,
            'floral' => 60,
            'stock' => 2
        ]);
    }
}
