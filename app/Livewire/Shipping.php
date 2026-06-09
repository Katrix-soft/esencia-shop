<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Información de Envío | Esencia')]
class Shipping extends Component
{
    public $address = '';
    public $city = '';
    public $postal_code = '';
    public $province = '';
    public $phone = '';
    public $shipping_method = 'standard'; // standard, express, pickup
    public $items = [];

    protected $rules = [
        'address' => 'required|string|min:5',
        'city' => 'required|string|min:3',
        'postal_code' => 'required|string|min:4',
        'province' => 'required|string',
        'phone' => 'required|string|min:8',
        'shipping_method' => 'required|in:standard,express,pickup',
    ];

    public function mount()
    {
        $this->items = session()->get('cart', []);
        
        // If the cart is empty, redirect back to cart
        if (count($this->items) === 0) {
            return redirect()->route('cart');
        }
    }

    public function getSubtotalProperty()
    {
        $subtotal = 0;
        foreach ($this->items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }

    public function getShippingCostProperty()
    {
        switch ($this->shipping_method) {
            case 'standard':
                return 4500;
            case 'express':
                return 6000;
            case 'pickup':
                return 0;
            default:
                return 4500;
        }
    }

    public function getTotalProperty()
    {
        return $this->getSubtotalProperty() + $this->shippingCost;
    }

    public function updatedShippingMethod()
    {
        // Recalculates dynamically when shipping method changes
    }

    public function proceedToPayment()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('payment-error');
            throw $e;
        }

        session()->put('shipping_info', [
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'province' => $this->province,
            'phone' => $this->phone,
            'shipping_method' => $this->shipping_method,
            'shipping_cost' => $this->shippingCost,
        ]);

        $preferenceItems = [];
        foreach ($this->items as $item) {
            $preferenceItems[] = [
                'title' => $item['name'] . ' (' . ($item['size'] ?? 'Decant 10ml') . ')',
                'quantity' => (int) $item['quantity'],
                'unit_price' => (float) $item['price'],
                'currency_id' => 'ARS',
            ];
        }

        if ($this->shippingCost > 0) {
            $preferenceItems[] = [
                'title' => 'Costo de Envío (' . ucfirst($this->shipping_method) . ')',
                'quantity' => 1,
                'unit_price' => (float) $this->shippingCost,
                'currency_id' => 'ARS',
            ];
        }

        $accessToken = config('services.mercadopago.access_token');

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->post('https://api.mercadopago.com/checkout/preferences', [
                    'items' => $preferenceItems,
                    'back_urls' => [
                        'success' => str_replace('http://', 'https://', route('cart')),
                        'failure' => str_replace('http://', 'https://', route('cart')),
                        'pending' => str_replace('http://', 'https://', route('cart')),
                    ],
                    'auto_return' => 'approved',
                ]);

            if ($response->successful()) {
                $preference = $response->json();
                $this->dispatch('open-mercadopago-modal', preferenceId: $preference['id'], total: $this->total);
            } else {
                logger()->error('Mercado Pago Preference creation failed', ['response' => $response->body()]);
                session()->flash('error', 'Hubo un error al conectar con Mercado Pago. Inténtalo de nuevo.');
                $this->dispatch('payment-error');
            }
        } catch (\Exception $e) {
            logger()->error('Mercado Pago Exception', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error al procesar el pago.');
            $this->dispatch('payment-error');
        }
    }

    public function processPayment($formData)
    {
        $accessToken = config('services.mercadopago.access_token');
        
        try {
            logger()->info('Processing payment with Mercado Pago Checkout API', ['formData' => $formData]);

            $payload = [
                'transaction_amount' => (float) $formData['transaction_amount'],
                'token' => $formData['token'] ?? null,
                'description' => 'Compra en Tienda Esencia',
                'installments' => isset($formData['installments']) ? (int) $formData['installments'] : 1,
                'payment_method_id' => $formData['payment_method_id'],
                'payer' => [
                    'email' => $formData['payer']['email'] ?? 'comprador@esencia.com',
                ]
            ];

            if (isset($formData['payer']['identification'])) {
                $payload['payer']['identification'] = [
                    'type' => $formData['payer']['identification']['type'],
                    'number' => $formData['payer']['identification']['number'],
                ];
            }

            $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->withHeaders([
                    'X-Idempotency-Key' => uniqid('esencia_', true),
                ])
                ->post('https://api.mercadopago.com/v1/payments', $payload);

            if ($response->successful()) {
                $payment = $response->json();
                
                // Add to admin orders session
                $orders = session()->get('admin_orders', []);
                $orderId = 'ESP-' . rand(1000, 9999);
                
                $itemStrings = [];
                foreach ($this->items as $item) {
                    $itemStrings[] = $item['quantity'] . 'x ' . $item['name'] . ' (' . ($item['size'] ?? 'Decant 10ml') . ')';
                }
                
                $orders[] = [
                    'id' => $orderId,
                    'customer' => auth()->check() ? auth()->user()->name : ($formData['payer']['email'] ?? 'Invitado'),
                    'items' => implode(', ', $itemStrings),
                    'total' => $this->total,
                    'status' => 'Pagado',
                    'date' => date('Y-m-d'),
                ];
                session()->put('admin_orders', $orders);

                // Add semillas to user if logged in!
                if (auth()->check()) {
                    $userId = auth()->id();
                    $currentSemillas = session()->get("semillas_{$userId}", 570);
                    // Add 1 semilla for each $100 spent
                    $addedSemillas = (int)($this->total / 100);
                    session()->put("semillas_{$userId}", $currentSemillas + $addedSemillas);

                    // Update the customer entry in admin_customers list as well!
                    $customers = session()->get('admin_customers', []);
                    $foundCustomer = false;
                    foreach ($customers as &$c) {
                        if ($c['email'] === auth()->user()->email) {
                            $c['semillas'] += $addedSemillas;
                            $c['purchases_count'] += 1;
                            $c['total_spent'] += $this->total;
                            $foundCustomer = true;
                            break;
                        }
                    }
                    if (!$foundCustomer) {
                        $customers[] = [
                            'id' => count($customers) + 1,
                            'name' => auth()->user()->name,
                            'email' => auth()->user()->email,
                            'profile' => 'Amaderado (50%)',
                            'semillas' => $addedSemillas,
                            'status' => 'Nuevo',
                            'purchases_count' => 1,
                            'total_spent' => $this->total,
                        ];
                    }
                    session()->put('admin_customers', $customers);
                }

                // Clear the cart on successful/pending payment
                session()->forget('cart');
                $this->items = [];
                
                return [
                    'success' => true,
                    'status' => $payment['status'],
                    'ticket_url' => $payment['transaction_details']['external_resource_url'] ?? null,
                ];
            } else {
                logger()->error('Mercado Pago Payment creation failed', ['response' => $response->body()]);
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? 'Error desconocido al procesar el pago.';
                return [
                    'success' => false,
                    'error' => 'Error de Mercado Pago: ' . $errorMessage,
                ];
            }
        } catch (\Exception $e) {
            logger()->error('Mercado Pago Payment Exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Ocurrió un error excepcional al procesar tu pago: ' . $e->getMessage(),
            ];
        }
    }

    public function render()
    {
        return view('livewire.shipping');
    }
}
