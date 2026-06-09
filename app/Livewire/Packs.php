<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Esencia - Packs & Colecciones')]
class Packs extends Component
{
    public function render()
    {
        return view('livewire.packs');
    }
}
