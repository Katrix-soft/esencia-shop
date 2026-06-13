<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\ClubCoupon;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class ClubPointsService
{
    /**
     * Calcula los puntos que otorga un producto.
     * Ratio: 100 pesos = 1 punto (Precio * 0.01) * multiplicador
     *
     * @param Product $product
     * @return int
     */
    public function calculatePointsForProduct(Product $product): int
    {
        $basePoints = $product->price * 0.01;
        $multiplier = $product->points_multiplier ?? 1.0;
        
        return (int) round($basePoints * $multiplier);
    }

    /**
     * Otorga puntos al usuario en base a los productos de una orden completada.
     *
     * @param User $user
     * @param Order $order
     * @return int Total de puntos ganados
     */
    public function addPointsForOrder(User $user, Order $order): int
    {
        $totalPoints = 0;

        DB::transaction(function () use ($user, $order, &$totalPoints) {
            foreach ($order->items as $item) {
                // Asumiendo que el item tiene relacion con el producto o se puede obtener
                $product = $item->product ?? Product::find($item->product_id);
                if ($product) {
                    $points = $this->calculatePointsForProduct($product) * $item->quantity;
                    $totalPoints += $points;
                }
            }

            if ($totalPoints > 0) {
                $user->increment('club_points', $totalPoints);

                PointTransaction::create([
                    'user_id' => $user->id,
                    'points' => $totalPoints,
                    'type' => 'earned',
                    'description' => "Puntos obtenidos por la compra #{$order->order_number}",
                    'reference_type' => Order::class,
                    'reference_id' => $order->id,
                ]);
            }
        });

        return $totalPoints;
    }

    /**
     * Canjea puntos por un cupón.
     * Genera un código de descuento que puede usar en el checkout.
     *
     * @param User $user
     * @param ClubCoupon $coupon
     * @return string Código del cupón generado
     * @throws Exception Si no hay puntos suficientes
     */
    public function redeemCoupon(User $user, ClubCoupon $coupon): string
    {
        if ($user->club_points < $coupon->points_required) {
            throw new Exception("Puntos insuficientes para canjear este cupón.");
        }

        $couponCode = null;

        DB::transaction(function () use ($user, $coupon, &$couponCode) {
            $user->decrement('club_points', $coupon->points_required);

            PointTransaction::create([
                'user_id' => $user->id,
                'points' => -$coupon->points_required,
                'type' => 'redeemed',
                'description' => "Canje de puntos por: {$coupon->name}",
                'reference_type' => ClubCoupon::class,
                'reference_id' => $coupon->id,
            ]);

            // Generar código único para el usuario.
            // Si hay una tabla "coupons" estándar, aquí se insertaría el registro.
            // Por ahora, generamos el string y el sistema deberá validarlo luego en el checkout.
            $couponCode = 'CLUB-' . strtoupper(Str::random(8));
        });

        return $couponCode;
    }
}
