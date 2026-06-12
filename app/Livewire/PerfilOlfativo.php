<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Tu ADN Olfativo | Esencia')]
class PerfilOlfativo extends Component
{
    // Estado del Perfil
    public $woodPercent = 65;
    public $citrusPercent = 20;
    public $floralPercent = 15;
    public $affinityScore = 88;
    public $preferredNotes = ['Sándalo', 'Bergamota', 'Vetiver', 'Lavanda'];
    
    // Recomendaciones
    public $recommendations = [];

    // Estado del Cuestionario
    public $inTest = false;
    public $currentStep = 1;
    public $totalSteps = 3;
    
    // Respuestas
    public $selectedAroma = '';
    public $selectedOccasion = '';
    public $selectedIntensity = '';

    public $isAnalyzing = false;

    public function mount()
    {
        // Cargar desde sesión si ya existe
        if (session()->has('olfactive_profile')) {
            $profile = session()->get('olfactive_profile');
            $this->woodPercent = $profile['woodPercent'];
            $this->citrusPercent = $profile['citrusPercent'];
            $this->floralPercent = $profile['floralPercent'];
            $this->affinityScore = $profile['affinityScore'];
            $this->preferredNotes = $profile['preferredNotes'];
        }
        $this->loadRecommendations();
    }

    public function startTest()
    {
        $this->inTest = true;
        $this->currentStep = 1;
        $this->selectedAroma = '';
        $this->selectedOccasion = '';
        $this->selectedIntensity = '';
        $this->isAnalyzing = false;
    }

    public function nextStep()
    {
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        } else {
            $this->runAnalysis();
        }
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function runAnalysis()
    {
        $this->isAnalyzing = true;
    }

    public function completeTest()
    {
        $this->isAnalyzing = false;
        
        // Calcular perfil basado en las respuestas
        if ($this->selectedAroma === 'amaderado') {
            $this->woodPercent = 75;
            $this->citrusPercent = 15;
            $this->floralPercent = 10;
            $this->preferredNotes = ['Sándalo', 'Cedro', 'Vetiver', 'Pachulí'];
        } elseif ($this->selectedAroma === 'citrico') {
            $this->woodPercent = 15;
            $this->citrusPercent = 70;
            $this->floralPercent = 15;
            $this->preferredNotes = ['Mandarina', 'Bergamota', 'Limón', 'Neroli'];
        } elseif ($this->selectedAroma === 'floral') {
            $this->woodPercent = 10;
            $this->citrusPercent = 20;
            $this->floralPercent = 70;
            $this->preferredNotes = ['Jazmín', 'Rosa', 'Lavanda', 'Vainilla'];
        } else { // oriental
            $this->woodPercent = 40;
            $this->citrusPercent = 10;
            $this->floralPercent = 50;
            $this->preferredNotes = ['Ámbar', 'Canela', 'Cardamomo', 'Tabaco'];
        }

        // Variar afinidad según la combinación
        if ($this->selectedIntensity === 'sutil') {
            $this->affinityScore = 93;
        } elseif ($this->selectedIntensity === 'audaz') {
            $this->affinityScore = 97;
        } else {
            $this->affinityScore = 95;
        }

        // Guardar en sesión
        session()->put('olfactive_profile', [
            'woodPercent' => $this->woodPercent,
            'citrusPercent' => $this->citrusPercent,
            'floralPercent' => $this->floralPercent,
            'affinityScore' => $this->affinityScore,
            'preferredNotes' => $this->preferredNotes,
        ]);

        $this->loadRecommendations();
        $this->inTest = false;

        session()->flash('profile_success', '¡Tu ADN Olfativo ha sido recalculado con éxito!');
    }

    public function loadRecommendations()
    {
        $query = \App\Models\Product::query();
        
        if ($this->woodPercent >= 50) {
            $query->orderBy('wood', 'desc');
        } elseif ($this->citrusPercent >= 50) {
            $query->orderBy('citrus', 'desc');
        } else {
            $query->orderBy('floral', 'desc');
        }
        
        $products = $query->take(3)->get();
        
        $this->recommendations = $products->map(function($product, $index) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'type' => $product->category ? $product->category->name : 'Fragancia',
                'price' => $product->discounted_price,
                'original_price' => $product->price,
                'discount' => $product->discount,
                'tag' => $index === 0 ? 'Best Match ' . $this->affinityScore . '%' : ($index === 1 ? 'Recomendado' : ''),
                'img' => $product->image ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuDcAgmDQu9wsF0GkVx-_K_WSdHgdQ0y6G1Zcjoph0318i5BuRNfXhNH3xnTwYMa55Paovt44ti3KvlN0pf0UkSPfQpP9QWRZLUvI2O2WFMpAgQRR4yO8kE0IHDFrYXe93fZa5sjcqeD9wxPKDv6FqDOCUVhpUa6AH2bMskrpYkYQKNQA6tJUfKGNiq7X9hG0nbYg9AMHzkeTrAjvdF9VBgXkErBlEsQrux2dnzZa6-wOC-plHcBpUhPOPL-J7gh5m53mEzDvNwRocU',
            ];
        })->toArray();
    }

    public function addToCart($productId)
    {
        $product = collect($this->recommendations)->firstWhere('id', $productId);
        if ($product) {
            $cart = \Gloudemans\Shoppingcart\Facades\Cart::instance('default');
            $item = $cart->search(function ($cartItem, $rowId) use ($product) {
                return $cartItem->id === $product['id'];
            })->first();

            if ($item) {
                $cart->update($item->rowId, $item->qty + 1);
            } else {
                $cart->add(
                    $product['id'],
                    $product['name'],
                    1,
                    $product['price'],
                    [
                        'image' => $product['img'], 
                        'type' => $product['type'],
                        'original_price' => $product['original_price'] ?? $product['price'],
                        'discount' => $product['discount'] ?? 0
                    ]
                );
            }
            $this->dispatch('cart-updated');
            
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Agregado',
                'text' => "{$product['name']} añadido al carrito."
            ]);
        }
    }

    public function render()
    {
        return view('livewire.perfil-olfativo');
    }
}
