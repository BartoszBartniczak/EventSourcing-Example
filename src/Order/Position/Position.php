<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Position;

use BartoszBartniczak\EventSourcing\Shop\Product\Product;

class Position
{

    /**
     * @var Product
     */
    private $product;

    /**
     * @var float
     */
    private $quantity;

    /**
     * Position constructor.
     * @param Product $product
     * @param float $quantity
     */
    public function __construct(Product $product, float $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

}