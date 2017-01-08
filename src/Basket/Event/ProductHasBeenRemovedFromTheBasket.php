<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Event\Id;
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
     * @param Basket $basket
     * @param ProductId $productId
     */
    public function __construct(Id $eventId, \DateTime $dateTime, Basket $basket, ProductId $productId)
    {
        parent::__construct($eventId, $dateTime, $basket);
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