<?php

namespace KatrixSoft\Cart\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use KatrixSoft\Cart\Livewire\Forms\CreateAddressForm;
use KatrixSoft\Cart\Models\Address;
use KatrixSoft\Cart\Models\Order;
use KatrixSoft\Cart\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Gloudemans\Shoppingcart\Facades\Cart;

class Checkout extends Component
{
    use WithFileUploads;

    public CreateAddressForm $form;

    // Datos de transferencia bancaria
    public $transfer_issuer_name;
    public $transfer_issuer_cuit;
    public $transfer_receipt;

    // Wizard Steps: 1 = Envío, 2 = Pago, 3 = Confirmación
    public int $step = 1;

    // Dirección de envío
    public $addresses      = [];
    public $selectedAddressId = null;
    public bool $newAddress   = false;
    public array $localities  = [];

    // Método de pago
    public string $paymentMethod = 'mercadopago';

    // Datos de Mercado Pago
    public $mpPaymentId     = null;
    public $mpPaymentStatus = null;

    // Orden creada (step 3)
    public ?Order $createdOrder = null;

    protected $listeners = [
        'addressSelected'   => 'selectAddress',
        'mpPaymentApproved' => 'placeOrderWithMP',
    ];

    public function mount(): mixed
    {
        $instance = cart_instance();

        if (Cart::instance($instance)->count() == 0 && $this->step != 3) {
            return redirect()->route(config('katrix-cart.cart_route', 'cart.index'));
        }

        // Establecer método de pago por defecto desde config
        $methods = config('katrix-cart.payment_methods', ['mercadopago']);
        $this->paymentMethod = $methods[0] ?? 'mercadopago';

        $this->loadAddresses();

        $defaultAddress = collect($this->addresses)->firstWhere('is_default', true);
        if ($defaultAddress) {
            $this->selectedAddressId = $defaultAddress->id;
        } elseif (count($this->addresses) > 0) {
            $this->selectedAddressId = $this->addresses[0]->id;
        }

        return null;
    }

    public function loadAddresses(): void
    {
        $this->addresses = auth()->user()->addresses()->orderBy('is_default', 'desc')->get();
    }

    public function selectAddress(int $id): void
    {
        $this->selectedAddressId = $id;
    }

    public function updatedFormProvince(string $value): void
    {
        if ($value) {
            $response = Http::get('https://apis.datos.gob.ar/georef/api/localidades', [
                'provincia' => $value,
                'campos'    => 'id,nombre',
                'max'       => 1000,
            ]);

            if ($response->successful()) {
                $this->localities = collect($response->json()['localidades'])
                    ->sortBy('nombre')
                    ->values()
                    ->toArray();
            }
        } else {
            $this->localities = [];
        }

        $this->form->locality  = '';
        $this->form->zip_code  = '';
    }

    public function updatedFormLocality(string $value): void
    {
        $suggested = \KatrixSoft\Cart\Services\ArgentineLocations::getZipCode(
            $this->form->province,
            $value
        );

        if ($suggested) {
            $this->form->zip_code = $suggested;
        }

        if ($value) {
            $this->form->district = $value;
        }
    }

    public function edit(int $id): void
    {
        $address = auth()->user()->addresses()->find($id);
        if (! $address) return;

        $this->form->setAddress($address);
        $this->updatedFormProvince($address->province);
        $this->form->locality = $address->locality;
        $this->newAddress     = true;
    }

    public function delete(int $id): void
    {
        auth()->user()->addresses()->where('id', $id)->delete();
        $this->loadAddresses();

        if ($this->selectedAddressId == $id) {
            $this->selectedAddressId = count($this->addresses) > 0
                ? $this->addresses[0]->id
                : null;
        }

        $this->dispatch('swal', [
            'icon'               => 'success',
            'title'              => 'Dirección eliminada',
            'text'               => 'La dirección ha sido removida.',
            'confirmButtonColor' => '#7c3aed',
        ]);
    }

    public function saveAddress(): void
    {
        $isEdit = ! empty($this->form->addressId);
        $result = $this->form->save();

        $this->newAddress = false;
        $this->loadAddresses();

        if ($result && isset($result->id)) {
            $this->selectedAddressId = $result->id;
        }

        $this->dispatch('swal', [
            'icon'               => 'success',
            'title'              => $isEdit ? '¡Dirección actualizada!' : '¡Dirección guardada!',
            'text'               => 'Se ha guardado la dirección correctamente.',
            'confirmButtonColor' => '#7c3aed',
        ]);
    }

    public function goToPayment(): mixed
    {
        $instance   = cart_instance();
        $cart       = Cart::instance($instance)->content();
        $stockModel = cart_stock_model();

        if ($cart->count() == 0) {
            $this->dispatch('swal', [
                'icon'               => 'warning',
                'title'              => 'Carrito vacío',
                'text'               => 'Debes tener productos en tu carrito para continuar.',
                'confirmButtonColor' => '#7c3aed',
            ]);
            return null;
        }

        $itemIds = $cart->pluck('id')->toArray();
        $stocks  = $stockModel::whereIn('id', $itemIds)
            ->get()
            ->mapWithKeys(fn($p) => [$p->getId() => $p->getStock()])
            ->toArray();

        $hasValidItems = false;
        foreach ($cart as $item) {
            if (($stocks[$item->id] ?? 0) >= $item->qty) {
                $hasValidItems = true;
                break;
            }
        }

        if (! $hasValidItems) {
            return redirect()->route(config('katrix-cart.cart_route', 'cart.index'));
        }

        if (! $this->selectedAddressId) {
            $this->dispatch('swal', [
                'icon'               => 'error',
                'title'              => 'Dirección requerida',
                'text'               => 'Por favor, selecciona o agrega una dirección de envío.',
                'confirmButtonColor' => '#7c3aed',
            ]);
            return null;
        }

        $this->step = 2;
        return null;
    }

    public function backToShipping(): void
    {
        $this->step = 1;
    }

    /**
     * Llamado desde evento JS cuando Mercado Pago aprueba el pago.
     */
    public function placeOrderWithMP(string $mpPaymentId, string $mpStatus, ?string $mpPaymentType = null): void
    {
        $this->mpPaymentId     = $mpPaymentId;
        $this->mpPaymentStatus = $mpStatus;
        $this->paymentMethod   = 'mercadopago' . ($mpPaymentType ? '_' . $mpPaymentType : '');
        $this->placeOrder();
    }

    public function placeOrder(): void
    {
        // Validar comprobante si es transferencia bancaria
        if ($this->paymentMethod === 'bank_transfer') {
            $this->validate([
                'transfer_issuer_name' => 'required|string|max:255',
                'transfer_issuer_cuit' => 'required|string|max:255',
                'transfer_receipt'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ], [
                'transfer_issuer_name.required' => 'El nombre del titular es obligatorio.',
                'transfer_issuer_cuit.required' => 'El CUIT/CUIL es obligatorio.',
                'transfer_receipt.required'     => 'Debes adjuntar un comprobante válido.',
                'transfer_receipt.mimes'        => 'El comprobante debe ser una imagen o PDF.',
                'transfer_receipt.max'          => 'El comprobante no debe superar los 5MB.',
            ]);
        }

        // Verificar límite mensual de órdenes (si está configurado)
        $limit = config('katrix-cart.tenant_orders_limit');
        if ($limit !== null) {
            $ordersThisMonth = Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            if ($ordersThisMonth >= $limit) {
                $this->dispatch('swal', [
                    'icon'               => 'error',
                    'title'              => 'Límite de Pedidos Excedido',
                    'text'               => 'Lo sentimos, esta tienda ha superado su límite mensual de pedidos. Por favor, intente más tarde.',
                    'confirmButtonColor' => '#7c3aed',
                ]);
                return;
            }
        }

        // Dirección seleccionada
        $addressObj = Address::find($this->selectedAddressId);
        if (! $addressObj) {
            $this->dispatch('swal', [
                'icon'               => 'error',
                'title'              => 'Error de Dirección',
                'text'               => 'No pudimos encontrar la dirección seleccionada.',
                'confirmButtonColor' => '#7c3aed',
            ]);
            return;
        }

        $addressSnapshot = [
            'type'        => $addressObj->type,
            'description' => $addressObj->description,
            'province'    => $addressObj->province,
            'locality'    => $addressObj->locality,
            'zip_code'    => $addressObj->zip_code,
            'district'    => $addressObj->district,
            'address'     => $addressObj->address,
            'apartment'   => $addressObj->apartment,
            'reference'   => $addressObj->reference,
            'contact'     => $addressObj->contact,
            'phone'       => $addressObj->phone,
        ];

        $instance   = cart_instance();
        $stockModel = cart_stock_model();

        try {
            $order = DB::transaction(function () use ($addressSnapshot, $instance, $stockModel) {
                $cartContent = Cart::instance($instance)->content();
                $itemIds     = $cartContent->pluck('id')->toArray();

                // Bloqueo pesimista para evitar sobreventas concurrentes
                $products = $stockModel::whereIn('id', $itemIds)->lockForUpdate()->get()->keyBy('id');

                $validItems  = [];
                $subtotalVal = 0;

                foreach ($cartContent as $item) {
                    $product = $products->get($item->id);
                    $stock   = $product ? $product->getStock() : 0;

                    if ($item->qty <= $stock) {
                        $validItems[] = $item;
                        $subtotalVal += $item->qty * $item->price;
                    }
                }

                if (empty($validItems)) {
                    throw new \Exception('NO_STOCK');
                }

                $shippingCost  = config('katrix-cart.free_shipping', true) ? 0.00 : 0.00;
                $totalVal      = $subtotalVal + $shippingCost;

                $paymentStatus = 'pending';
                if (str_starts_with($this->paymentMethod, 'mercadopago') && $this->mpPaymentStatus === 'approved') {
                    $paymentStatus = 'paid';
                }

                $order = Order::create([
                    'user_id'               => auth()->id(),
                    'shipping_address'      => $addressSnapshot,
                    'payment_method'        => $this->paymentMethod,
                    'payment_status'        => $paymentStatus,
                    'mp_payment_id'         => $this->mpPaymentId,
                    'status'                => 'pending',
                    'shipping_cost'         => $shippingCost,
                    'subtotal'              => $subtotalVal,
                    'total'                 => $totalVal,
                    'transfer_issuer_name'  => $this->transfer_issuer_name,
                    'transfer_issuer_cuit'  => $this->transfer_issuer_cuit,
                    'transfer_receipt_path' => $this->transfer_receipt
                        ? $this->transfer_receipt->store('receipts', 'public')
                        : null,
                ]);

                foreach ($validItems as $item) {
                    $features = [];
                    if ($item->options && isset($item->options->features)) {
                        $features = $item->options->features;
                    }

                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $item->id,
                        'name'       => $item->name,
                        'quantity'   => $item->qty,
                        'price'      => $item->price,
                        'features'   => $features,
                    ]);

                    // Descontar stock atómicamente
                    $product = $products->get($item->id);
                    if ($product) {
                        $product->decrement('stock', $item->qty);
                    }
                }

                return $order;
            });

        } catch (\Exception $e) {
            if ($e->getMessage() === 'NO_STOCK') {
                $this->dispatch('swal', [
                    'icon'               => 'error',
                    'title'              => 'Sin stock disponible',
                    'text'               => 'Lo sentimos, ningún producto en tu carrito tiene stock disponible en este momento.',
                    'confirmButtonColor' => '#7c3aed',
                ]);
                return;
            }
            throw $e;
        }

        // Limpiar carrito (fuera de la transacción DB)
        $cartContent     = Cart::instance($instance)->content();
        $rowIdsToRemove  = [];

        foreach ($cartContent as $item) {
            if ($order->items()->where('product_id', $item->id)->exists()) {
                $rowIdsToRemove[] = $item->rowId;
            }
        }

        foreach ($rowIdsToRemove as $rowId) {
            Cart::instance($instance)->remove($rowId);
        }

        if (auth()->check()) {
            try {
                DB::table('shoppingcart')->where('identifier', auth()->id())->delete();
            } catch (\Exception) {
                // Ignorar error al limpiar shoppingcart
            }
            Cart::instance($instance)->store(auth()->id());
        }

        $this->createdOrder = $order;
        $this->step         = 3;

        $this->dispatch('cart-updated');
        $this->dispatch('swal', [
            'icon'               => 'success',
            'title'              => '¡Compra completada con éxito!',
            'text'               => 'Tu pedido se registró bajo el código #' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'confirmButtonColor' => '#7c3aed',
        ]);
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

        return view('cart::livewire.checkout', [
            'stocks'          => $stocks,
            'hasStockErrors'  => $hasStockErrors,
            'hasValidItems'   => $hasValidItems,
            'subtotal'        => $subtotalVal,
            'total'           => $subtotalVal,
            'totalAmount'     => $subtotalVal,
            'mpPublicKey'     => config('katrix-cart.mercadopago.public_key', ''),
            'paymentMethods'  => config('katrix-cart.payment_methods', ['mercadopago']),
        ]);
    }
}
