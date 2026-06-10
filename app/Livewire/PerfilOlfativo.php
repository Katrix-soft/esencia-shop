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
        if ($this->woodPercent >= 50) {
            $this->recommendations = [
                [
                    'name' => 'Aventus Premium',
                    'type' => 'Eau de Parfum',
                    'price' => 180.00,
                    'tag' => 'Best Match ' . $this->affinityScore . '%',
                    'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDcAgmDQu9wsF0GkVx-_K_WSdHgdQ0y6G1Zcjoph0318i5BuRNfXhNH3xnTwYMa55Paovt44ti3KvlN0pf0UkSPfQpP9QWRZLUvI2O2WFMpAgQRR4yO8kE0IHDFrYXe93fZa5sjcqeD9wxPKDv6FqDOCUVhpUa6AH2bMskrpYkYQKNQA6tJUfKGNiq7X9hG0nbYg9AMHzkeTrAjvdF9VBgXkErBlEsQrux2dnzZa6-wOC-plHcBpUhPOPL-J7gh5m53mEzDvNwRocU',
                ],
                [
                    'name' => 'Bleu de Paris',
                    'type' => 'Intense Extract',
                    'price' => 145.00,
                    'tag' => 'Recomendado',
                    'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDFBrmGSlh4Yvs4kfiZh5vOlDkNSOLlcpBrwjbCTIl6oXi9kJ0Z7SsYzjhvQ_sAthjOSXfB1N2y5p5nV4JG4TYfjVNechy_oZUrjYKXNqmERKsyHsGzRGLhxP8RMa-glzQt7Hupq8nGkwXd0ZGltBVgbNG1s1VrPihRUs2gr3DFso6TlnUzEuiBD9nrDIe6_BnaIr0O-PaD_q04uUZIQ7fL4-V_p-IYVL_NYs2DlxjMHidi8G1qkQAmqf4TM98h06P8P4BFwJ77rbs',
                ],
                [
                    'name' => 'Essence of Woods',
                    'type' => 'Discovery Set',
                    'price' => 65.00,
                    'tag' => '',
                    'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDHR4k-t_MrPB6TQIHI1db-MtClWDTQMfVz5cyCi_v-s7iZYu6hALpW-M3wpHRQF6EwpF1bBkHoX-43oL3u6kTIAgbMW0qKLy-mvqdGq2lV7GFz0eYfmEcCZnvc84arhL-XFVrQLfwircc1DVhyQo8cHGVpF9ttbCssndb23TAKwIAmbnn0pqwSuKWELYYEgX7h2-XAdCMiX7TlFfZPn0QG-W1ffW40fT4G3thucdW3WzR0dgU1tmw9i6WtbllUV7c_V3gU7tFLEKM',
                ],
            ];
        } elseif ($this->citrusPercent >= 50) {
            $this->recommendations = [
                [
                    'name' => 'Bergamote Soleil',
                    'type' => 'Cologne Absolue',
                    'price' => 160.00,
                    'tag' => 'Best Match ' . $this->affinityScore . '%',
                    'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDcAgmDQu9wsF0GkVx-_K_WSdHgdQ0y6G1Zcjoph0318i5BuRNfXhNH3xnTwYMa55Paovt44ti3KvlN0pf0UkSPfQpP9QWRZLUvI2O2WFMpAgQRR4yO8kE0IHDFrYXe93fZa5sjcqeD9wxPKDv6FqDOCUVhpUa6AH2bMskrpYkYQKNQA6tJUfKGNiq7X9hG0nbYg9AMHzkeTrAjvdF9VBgXkErBlEsQrux2dnzZa6-wOC-plHcBpUhPOPL-J7gh5m53mEzDvNwRocU',
                ],
                [
                    'name' => 'Neroli Portofino',
                    'type' => 'Eau de Parfum',
                    'price' => 220.00,
                    'tag' => 'Fresco',
                    'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDFBrmGSlh4Yvs4kfiZh5vOlDkNSOLlcpBrwjbCTIl6oXi9kJ0Z7SsYzjhvQ_sAthjOSXfB1N2y5p5nV4JG4TYfjVNechy_oZUrjYKXNqmERKsyHsGzRGLhxP8RMa-glzQt7Hupq8nGkwXd0ZGltBVgbNG1s1VrPihRUs2gr3DFso6TlnUzEuiBD9nrDIe6_BnaIr0O-PaD_q04uUZIQ7fL4-V_p-IYVL_NYs2DlxjMHidi8G1qkQAmqf4TM98h06P8P4BFwJ77rbs',
                ],
                [
                    'name' => 'Citrus Infusion',
                    'type' => 'Discovery Set',
                    'price' => 50.00,
                    'tag' => '',
                    'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDHR4k-t_MrPB6TQIHI1db-MtClWDTQMfVz5cyCi_v-s7iZYu6hALpW-M3wpHRQF6EwpF1bBkHoX-43oL3u6kTIAgbMW0qKLy-mvqdGq2lV7GFz0eYfmEcCZnvc84arhL-XFVrQLfwircc1DVhyQo8cHGVpF9ttbCssndb23TAKwIAmbnn0pqwSuKWELYYEgX7h2-XAdCMiX7TlFfZPn0QG-W1ffW40fT4G3thucdW3WzR0dgU1tmw9i6WtbllUV7c_V3gU7tFLEKM',
                ],
            ];
        } else {
            $this->recommendations = [
                [
                    'name' => 'Jasmine Absolue',
                    'type' => 'Eau de Parfum',
                    'price' => 195.00,
                    'tag' => 'Best Match ' . $this->affinityScore . '%',
                    'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDcAgmDQu9wsF0GkVx-_K_WSdHgdQ0y6G1Zcjoph0318i5BuRNfXhNH3xnTwYMa55Paovt44ti3KvlN0pf0UkSPfQpP9QWRZLUvI2O2WFMpAgQRR4yO8kE0IHDFrYXe93fZa5sjcqeD9wxPKDv6FqDOCUVhpUa6AH2bMskrpYkYQKNQA6tJUfKGNiq7X9hG0nbYg9AMHzkeTrAjvdF9VBgXkErBlEsQrux2dnzZa6-wOC-plHcBpUhPOPL-J7gh5m53mEzDvNwRocU',
                ],
                [
                    'name' => 'Rose Seduction',
                    'type' => 'Intense Extract',
                    'price' => 160.00,
                    'tag' => 'Floral',
                    'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDFBrmGSlh4Yvs4kfiZh5vOlDkNSOLlcpBrwjbCTIl6oXi9kJ0Z7SsYzjhvQ_sAthjOSXfB1N2y5p5nV4JG4TYfjVNechy_oZUrjYKXNqmERKsyHsGzRGLhxP8RMa-glzQt7Hupq8nGkwXd0ZGltBVgbNG1s1VrPihRUs2gr3DFso6TlnUzEuiBD9nrDIe6_BnaIr0O-PaD_q04uUZIQ7fL4-V_p-IYVL_NYs2DlxjMHidi8G1qkQAmqf4TM98h06P8P4BFwJ77rbs',
                ],
                [
                    'name' => 'Floral Discovery',
                    'type' => 'Discovery Set',
                    'price' => 55.00,
                    'tag' => '',
                    'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDHR4k-t_MrPB6TQIHI1db-MtClWDTQMfVz5cyCi_v-s7iZYu6hALpW-M3wpHRQF6EwpF1bBkHoX-43oL3u6kTIAgbMW0qKLy-mvqdGq2lV7GFz0eYfmEcCZnvc84arhL-XFVrQLfwircc1DVhyQo8cHGVpF9ttbCssndb23TAKwIAmbnn0pqwSuKWELYYEgX7h2-XAdCMiX7TlFfZPn0QG-W1ffW40fT4G3thucdW3WzR0dgU1tmw9i6WtbllUV7c_V3gU7tFLEKM',
                ],
            ];
        }
    }

    public function addToCart($productName)
    {
        $product = collect($this->recommendations)->firstWhere('name', $productName);
        if ($product) {
            $cart = \Gloudemans\Shoppingcart\Facades\Cart::instance('default');
            $item = $cart->search(function ($cartItem, $rowId) use ($product) {
                return $cartItem->name === $product['name'];
            })->first();

            if ($item) {
                $cart->update($item->rowId, $item->qty + 1);
            } else {
                $cart->add(
                    uniqid(), // Assuming ID isn't linked to a real product table here
                    $product['name'],
                    1,
                    $product['price'] * 1000,
                    ['image' => $product['img'], 'type' => $product['type']]
                );
            }
            $this->dispatch('cart-updated');
        }
    }

    public function render()
    {
        return view('livewire.perfil-olfativo');
    }
}
