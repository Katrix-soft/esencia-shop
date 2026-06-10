<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Title('Esencia - Catálogo')]
class Catalog extends Component
{
    #[Url]
    public $search = '';

    public $selectedNotes = [];
    public $selectedFormats = [];

    public function getPopularNotesProperty()
    {
        $products = \App\Models\Product::whereNotNull('general_notes')->get('general_notes');
        $noteCounts = [];
        foreach ($products as $p) {
            if (is_array($p->general_notes)) {
                foreach ($p->general_notes as $note) {
                    $noteName = ucfirst(strtolower(trim($note)));
                    if (!isset($noteCounts[$noteName])) {
                        $noteCounts[$noteName] = 0;
                    }
                    $noteCounts[$noteName]++;
                }
            }
        }
        arsort($noteCounts);
        return array_keys(array_slice($noteCounts, 0, 15)); // Top 15 notes
    }

    public function getProductsProperty()
    {
        $query = \App\Models\Product::with('category')->where('stock', '>', 0);

        if (!empty($this->selectedNotes)) {
            $query->where(function ($q) {
                foreach ($this->selectedNotes as $note) {
                    // Fragella notes are typically title cased or specific, we check exact or lowercase
                    $q->orWhereJsonContains('general_notes', $note)
                      ->orWhereJsonContains('general_notes', strtolower($note))
                      ->orWhereJsonContains('general_notes', ucwords(strtolower($note)));
                }
            });
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.catalog');
    }
}
