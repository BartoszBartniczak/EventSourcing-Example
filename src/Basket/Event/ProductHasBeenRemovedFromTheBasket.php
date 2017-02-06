<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Event\Id;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;

class ProductHasBeenRemovedFromTheBasket extends Event
{

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * ProductHasBeenRemovedFromTheBasket constructor.
     * @param Id $eventId
     * @param \DateTime $dateTime
     * @param BasketId $basketIdId
     * @param ProductId $productId
     */
    public function __construct(Id $eventId, \DateTime $dateTime, BasketId $basketIdId, ProductId $productId)
    {
        parent::__construct($eventId, $dateTime, $basketIdId);
        $this->productId = $productId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

}