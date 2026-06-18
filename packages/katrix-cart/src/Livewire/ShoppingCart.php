<?php

namespace KatrixSoft\Cart\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

class ShoppingCart extends Component
{
    public function increaseQty(string $rowId): void
    {
        $instance  = cart_instance();
        $item      = Cart::instance($instance)->get($rowId);
        $stockModel = cart_stock_model();

        // Validar stock real desde el modelo configurado
        $product = $stockModel::findForCart($item->id);
        $stock   = $product ? $product->getStock() : 0;

        if ($item->qty >= $stock) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Sin stock disponible',
                'text'  => 'No hay suficiente stock para agregar más unidades.',
            ]);
            return;
        }

        Cart::instance($instance)->update($rowId, $item->qty + 1);
        $this->syncCart();
    }

    public function decreaseQty(string $rowId): void
    {
        $instance = cart_instance();
        $item     = Cart::instance($instance)->get($rowId);

        if ($item->qty > 1) {
            Cart::instance($instance)->update($rowId, $item->qty - 1);
            $this->syncCart();
        }
    }

    public function removeItem(string $rowId): void
    {
        Cart::instance(cart_instance())->remove($rowId);
        $this->syncCart();
        $this->dispatch('cart-updated');
    }

    public function clearCart(): void
    {
        Cart::instance(cart_instance())->destroy();
        $this->syncCart();
        $this->dispatch('cart-updated');
    }

    public function checkout(): mixed
    {
        // Validación en servidor antes de redirigir
        $cart       = Cart::instance(cart_instance())->content();
        $stockModel = cart_stock_model();
        $itemIds    = $cart->pluck('id')->toArray();

        $stocks = $stockModel::whereIn('id', $itemIds)
            ->get()
            ->mapWithKeys(fn($p) => [$p->getId() => $p->getStock()])
            ->toArray();

        $hasValidItems = false;
        foreach ($cart as $item) {
            $stock = $stocks[$item->id] ?? 0;
            if ($item->qty <= $stock) {
                $hasValidItems = true;
                break;
            }
        }

        if (! $hasValidItems) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Sin stock disponible',
                'text'  => 'No tienes ningún producto con stock disponible para comprar.',
            ]);
            return null;
        }

        return redirect()->route('checkout');
    }

    protected function syncCart(): void
    {
        if (auth()->check()) {
            Cart::instance(cart_instance())->store(auth()->id());
        }
    }

    public function render(): \Illuminate\View\View
    {
        $instance   = cart_instance();
        $cart       = Cart::instance($instance)->content();
        $stockModel = cart_stock_model();

        $itemIds = $cart->pluck('id')->toArray();
        $stocks  = $itemIds
            ? $stockModel::whereIn('id', $itemIds)
                ->get()
                ->mapWithKeys(fn($p) => [$p->getId() => $p->getStock()])
                ->toArray()
            : [];

        $subtotalVal    = 0;
        $hasStockErrors = false;
        $hasValidItems  = false;

        foreach ($cart as $item) {
            $stock = $stocks[$item->id] ?? 0;
            if ($item->qty <= $stock) {
                $subtotalVal   += $item->qty * $item->price;
                $hasValidItems  = true;
            } else {
                $hasStockErrors = true;
            }
        }

        return view('cart::livewire.shopping-cart', [
            'cart'           => $cart,
            'stocks'         => $stocks,
            'hasStockErrors' => $hasStockErrors,
            'hasValidItems'  => $hasValidItems,
            'total'          => $subtotalVal,
            'subtotal'       => $subtotalVal,
        ]);
    }
}
