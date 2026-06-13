<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Pack;

#[Title('Esencia - Packs & Colecciones')]
class Packs extends Component
{
    public function mount()
    {
        if (!cache('packs_section_enabled', true)) {
            return redirect()->route('catalog');
        }
    }
    public $packsData = [
        'discovery' => [
            'name' => 'Discovery Set',
            'type' => 'Set de Viaje',
            'price' => 450,
            'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBg6nOj4vgcjb-4YHKuxXEgutPmNd-RjxMBgM5VuEBNPUJSnfyLXTWvAWDzoYA0DW54_gjhX9hKAbcolUwE-FbsTzcIpO7lMcjwNyJWzcf0FifMgII43S0LuABrgkKY_NoINangWkCGEfQXvEkIsq-EKzlsGSKBv4DORcaLT02UGfh-68_ZD6Vk_s2g5iu5mcFbgjD2uuD7UPnam2HJ5GoLRW5rHsr4H4yRVFHh6pxvAZGUmguuQ7TgV2hxPJ6T4uQ3S5pmTO8LpQk',
        ],
        'giftbox' => [
            'name' => 'Terra Scent Gift Box',
            'type' => 'Regalo',
            'price' => 2400,
            'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBIS1hkh29GdCn5Rk9AZQcfLBUVRPe7c6F3xW8knpZTWT-zgTZ14cG6Rhz-OOe8pv1ki6Sv9iH2s5ouCo7F1wrmGaxN960gliVGyh6uw45Lr8vRTvLNmoMlXhPi3Q74f9riAio-7VfwDhZ0P9JvNw1wqxpyewhRvlmtr5kLIxcVZXXeYISzIe9u_T333nVCtcoMoOYyfy5Gm6uCyLjPXVsSZ7DLE1vWqdGa57z6OdS4F124d2W1IiphN-8L7csQKDA5jTswUl-VZpg',
        ],
        'exclusive' => [
            'name' => 'Exclusive Oud Collection',
            'type' => 'Colección Premium',
            'price' => 5200,
            'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDyr1k1jb8o8_2-Q-6FQWm9aPwUH0DaPx835Fni867mIBE_EBFQHyUFkW-9JHKTyOTWuKZlTcc9w9_RtxOWPKoIXV7_UY0nJQFkbuSFeqDKTcpAN8GbgsqqIPaIKZiMXM_wBACR8SbKg4Ud8OKAOt1dlCGA370vyvMrtK4cAbEnYAnhKI5IUxxO4VyuBpeKFPkZ4m4iHyxQba-F_u08gDlwiY-U6Pv1SQnbK6taW_JMWp9u9dECcUEaxRouIxdzQmoZkPmK95J8rQE',
        ]
    ];

    public function addToCart($packId)
    {
        if (str_starts_with($packId, 'pack_')) {
            $dbId = str_replace('pack_', '', $packId);
            $dbPack = Pack::find($dbId);
            if (!$dbPack) return;
            $pack = [
                'name' => $dbPack->name,
                'type' => 'Pack Exclusivo',
                'price' => $dbPack->discounted_price,
                'img' => $dbPack->image ? asset('storage/'.$dbPack->image) : '',
            ];
        } else {
            if (!isset($this->packsData[$packId])) return;
            $pack = $this->packsData[$packId];
        }
        
        $cart = \Gloudemans\Shoppingcart\Facades\Cart::instance('default');
        
        $item = $cart->search(function ($cartItem, $rowId) use ($pack) {
            return $cartItem->name === $pack['name'];
        })->first();

        if ($item) {
            $cart->update($item->rowId, $item->qty + 1);
        } else {
            $cart->add(
                $packId, // unique id for pack
                $pack['name'],
                1,
                $pack['price'],
                ['image' => $pack['img'], 'type' => $pack['type']]
            );
        }
        
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        $dbPacks = Pack::all();
        return view('livewire.packs', [
            'dbPacks' => $dbPacks
        ]);
    }
}
