<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Position;


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
        $this->changeQuantity($quantity);
    }

    public function changeQuantity(float $quantity)
    {
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
     * @param float $quantityToAdd
     */
    public function addToQuantity(float $quantityToAdd)
    {
        $this->changeQuantity($this->getQuantity() + $quantityToAdd);
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

}