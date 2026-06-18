# katrix-soft/cart

Librería Laravel/Livewire de carrito de compras y checkout reutilizable para proyectos Katrix-soft.

## Instalación

### 1. Agregar repositorio VCS en `composer.json` del proyecto:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/Katrix-soft/cart"
    }
],
```

### 2. Instalar:
```bash
composer require katrix-soft/cart:dev-main
```

### 3. Publicar configuración y migraciones:
```bash
php artisan vendor:publish --tag=cart-config
php artisan vendor:publish --tag=cart-migrations
php artisan migrate
```

### 4. Implementar `StockableProduct` en tu modelo:
```php
use KatrixSoft\Cart\Contracts\StockableProduct;

class Variant extends Model implements StockableProduct
{
    public function getStock(): int { return $this->stock; }
    public function getId(): int|string { return $this->id; }
    public static function findForCart($id): ?static { return static::find($id); }
}
```

### 5. Configurar `config/katrix-cart.php`:
```php
'stock_model' => App\Models\Variant::class,
'user_model'  => App\Models\User::class,
'home_route'  => 'welcome.index',
'payment_methods' => ['mercadopago'],
'mercadopago' => [
    'public_key'   => env('MP_PUBLIC_KEY'),
    'access_token' => env('MP_ACCESS_TOKEN'),
],
```

### 6. Agregar relación `addresses()` al modelo User:
```php
public function addresses()
{
    return $this->hasMany(\KatrixSoft\Cart\Models\Address::class);
}
```

### 7. Incluir componentes en tus vistas:
```blade
<livewire:cart::shopping-cart />
<livewire:cart::checkout />
```

### 8. Variables `.env` requeridas:
```env
MP_PUBLIC_KEY=your-mp-public-key
MP_ACCESS_TOKEN=your-mp-access-token
```

## Métodos de pago soportados
- `mercadopago` — Payment Brick
- `bank_transfer` — Transferencia bancaria con comprobante
- `cash` — Efectivo / Contraentrega

## Personalizar vistas:
```bash
php artisan vendor:publish --tag=cart-views
# Las vistas quedarán en resources/views/vendor/cart/
```
