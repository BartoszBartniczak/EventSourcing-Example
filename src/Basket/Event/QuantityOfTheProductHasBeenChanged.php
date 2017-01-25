<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Event\Id;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
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
     * @param BasketId $basketIdId
     * @param ProductId $productIdId
     * @param float $quantity
     */
    public function __construct(Id $eventId, \DateTime $dateTime, BasketId $basketIdId, ProductId $productIdId, float $quantity)
    {
        parent::__construct($eventId, $dateTime, $basketIdId);
        $this->productId = $productIdId;
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