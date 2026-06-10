<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class FragellaManualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Aseguramos de tener una categoría base
        $categoria = Category::firstOrCreate([
            'slug' => 'importados'
        ], [
            'name' => 'Importados',
            'color_class' => 'bg-surface-variant text-on-surface-variant'
        ]);

        $jsonPath = __DIR__ . '/fragella_manual_data.json';
        
        if (!file_exists($jsonPath)) {
            $this->command->error("Archivo JSON no encontrado.");
            return;
        }

        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);

        if (!$data) {
            $this->command->error("Error al decodificar el JSON.");
            return;
        }

        $similarJsonPath = __DIR__ . '/fragella_similar_data.json';
        if (file_exists($similarJsonPath)) {
            $similarJson = file_get_contents($similarJsonPath);
            $similarData = json_decode($similarJson, true);
            if (isset($similarData['similar_fragrances'])) {
                $data = array_merge($data, $similarData['similar_fragrances']);
            }
        }

        foreach ($data as $item) {
            Product::updateOrCreate(
                ['name' => $item['Name']], // Buscamos por nombre
                [
                    'category_id' => $categoria->id,
                    'description' => "Deliciosa fragancia de " . ($item['Brand'] ?? 'lujo') . ".",
                    'price' => isset($item['Price']) ? (float)$item['Price'] * 1000 : 99000, // Ajuste de precio
                    'image' => $item['Image URL'] ?? null,
                    'stock' => 10,
                    
                    // Fragella Fields
                    'fragella_id' => $item['_id'] ?? null,
                    'brand' => $item['Brand'] ?? null,
                    'year' => isset($item['Year']) ? (int) $item['Year'] : null,
                    'rating' => isset($item['rating']) ? (float) $item['rating'] : null,
                    'popularity' => $item['Popularity'] ?? null,
                    'gender' => $item['Gender'] ?? null,
                    'longevity' => $item['Longevity'] ?? null,
                    'sillage' => $item['Sillage'] ?? null,
                    'general_notes' => $item['General Notes'] ?? null,
                    'main_accords' => $item['Main Accords'] ?? null,
                    'main_accords_percentage' => $item['Main Accords Percentage'] ?? null,
                    'notes' => $item['Notes'] ?? null,
                ]
            );
            
            $this->command->info("✓ Importado: " . $item['Name']);
        }
        
        $this->command->info("Sincronización manual finalizada.");
    }
}
