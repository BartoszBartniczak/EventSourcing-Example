<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Position;


use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;

class Position
{

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var float
     */
    private $quantity;

    /**
     * Position constructor.
     * @param ProductId $productId
     * @param float $quantity
     */
    public function __construct(ProductId $productId, float $quantity)
    {
        $this->productId = $productId;
        $this->changeQuantity($quantity);
    }

    public function changeQuantity(float $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
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