<?php

namespace KatrixSoft\Cart\Contracts;

/**
 * Contrato que debe implementar el modelo de tu aplicación
 * que gestiona el stock de los productos del carrito.
 *
 * Ejemplo de implementación en tu proyecto:
 *
 *   class Variant extends Model implements StockableProduct
 *   {
 *       public function getStock(): int { return $this->stock; }
 *       public function getId(): int|string { return $this->id; }
 *       public static function findForCart($id): ?static
 *       {
 *           return static::find($id);
 *       }
 *   }
 */
interface StockableProduct
{
    /**
     * Devuelve el stock disponible actual.
     */
    public function getStock(): int;

    /**
     * Devuelve el identificador único del producto (usado como cart item id).
     */
    public function getId(): int|string;

    /**
     * Busca el modelo por ID para ser usado dentro del carrito.
     *
     * @param  int|string  $id
     * @return static|null
     */
    public static function findForCart($id): ?static;
}
