<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Services\FragellaService;

class SyncFragellaProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fragella:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza la información de los perfumes desde la API de Fragella';

    /**
     * Execute the console command.
     */
    public function handle(FragellaService $fragellaService)
    {
        $this->info('Iniciando sincronización con Fragella...');

        $products = Product::all();

        foreach ($products as $product) {
            $this->info("Buscando '{$product->name}'...");

            try {
                $response = $fragellaService->searchFragrances($product->name);

                // Handle direct array response OR nested 'data' key just in case
                $results = [];
                if (isset($response['data']) && is_array($response['data'])) {
                    $results = $response['data'];
                } elseif (is_array($response) && isset($response[0])) {
                    $results = $response;
                }

                if (count($results) > 0) {
                    $fragellaData = $results[0]; // Tomamos el mejor match

                    $product->update([
                        'fragella_id' => $fragellaData['_id'] ?? null,
                        'brand' => $fragellaData['Brand'] ?? null,
                        'year' => isset($fragellaData['Year']) ? (int) $fragellaData['Year'] : null,
                        'rating' => isset($fragellaData['rating']) ? (float) $fragellaData['rating'] : null,
                        'popularity' => $fragellaData['Popularity'] ?? null,
                        'gender' => $fragellaData['Gender'] ?? null,
                        'longevity' => $fragellaData['Longevity'] ?? null,
                        'sillage' => $fragellaData['Sillage'] ?? null,
                        'general_notes' => $fragellaData['General Notes'] ?? null,
                        'main_accords' => $fragellaData['Main Accords'] ?? null,
                        'main_accords_percentage' => $fragellaData['Main Accords Percentage'] ?? null,
                        'notes' => $fragellaData['Notes'] ?? null,
                    ]);

                    $this->info("✓ Actualizado: {$product->name}");
                } else {
                    $this->warn("⚠ No se encontró '{$product->name}' en Fragella.");
                    // Debug info:
                    if (isset($response['error'])) {
                        $this->error("API Error: " . $response['error']);
                    }
                }
            } catch (\Exception $e) {
                $this->error("Error con '{$product->name}': " . $e->getMessage());
            }
        }

        $this->info('Sincronización finalizada.');
    }
}
