<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray;

use BartoszBartniczak\EventSourcing\Shop\Product\Product;

class ProductIdStrategy implements KeyNamingStrategy
{
    public function fromProduct(Product $product): string
    {
        return $product->getId()->toNative();
    }


}