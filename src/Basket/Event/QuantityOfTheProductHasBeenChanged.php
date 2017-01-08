<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Event\Id;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\UUID\UUID;

class QuantityOfTheProductHasBeenChanged extends Event
{

    /**
     * @var UUID
     */
    private $productId;

    /**
     * @var float
     */
    private $quantity;

    /**
     * QuantityOfTheProductHasBeenChanged constructor.
     * @param Id $eventId
     * @param \DateTime $dateTime
     * @param Basket $basket
     * @param ProductId $productId
     * @param float $quantity
     */
    public function __construct(Id $eventId, \DateTime $dateTime, Basket $basket, ProductId $productId, float $quantity)
    {
        parent::__construct($eventId, $dateTime, $basket);
        $this->productId = $productId;
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
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }


}