<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;

class ChangeQuantityOfTheProduct implements Command
{

    /**
     * @var Basket
     */
    private $basket;

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var float
     */
    private $quantity;

    /**
     * ChangeQuantityOfTheProduct constructor.
     * @param Basket $basket
     * @param ProductId $productId
     * @param float $quantity
     */
    public function __construct(Basket $basket, ProductId $productId, float $quantity)
    {
        $this->basket = $basket;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    /**
     * @return Basket
     */
    public function getBasket(): Basket
    {
        return $this->basket;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

}