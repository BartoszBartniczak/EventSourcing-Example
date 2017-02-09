<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Event\Id;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;

class ProductHasBeenAddedToTheBasket extends Event
{
    /**
     * @var Product
     */
    private $product_id;

    /**
     * @var float
     */
    private $quantity;

    /**
     * ProductHasBeenAddedToTheBasket constructor.
     * @param Id $eventId
     * @param \DateTime $dateTime
     * @param BasketId $basketId
     * @param ProductId $productId
     * @param float $quantity
     */
    public function __construct(Id $eventId, \DateTime $dateTime, BasketId $basketId, ProductId $productId, float $quantity)
    {
        parent::__construct($eventId, $dateTime, $basketId);
        $this->product_id = $productId;
        $this->quantity = $quantity;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->product_id;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

}