<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Event\Id;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;

class ProductHasBeenAddedToTheBasket extends Event
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
     * ProductHasBeenAddedToTheBasket constructor.
     * @param Id $eventId
     * @param \DateTime $eventDateTime
     * @param Basket $basket
     * @param Product $product
     * @param float $quantity
     */
    public function __construct(Id $eventId, \DateTime $eventDateTime, Basket $basket, Product $product, float $quantity)
    {
        parent::__construct($eventId, $eventDateTime, $basket);
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