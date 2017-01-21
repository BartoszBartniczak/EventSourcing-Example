<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Event;

use BartoszBartniczak\EventSourcing\Event\Id;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Order\Id as OrderId;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray;


class OrderHasBeenCreated extends Event
{

    /**
     * @var BasketId
     */
    private $basketId;

    /**
     * @var PositionArray
     */
    private $positions;

    /**
     * OrderHasBeenCreated constructor.
     * @param Id $eventId
     * @param \DateTime $dateTime
     * @param OrderId $orderId
     * @param BasketId $basketId
     * @param PositionArray $positions
     */
    public function __construct(Id $eventId, \DateTime $dateTime, OrderId $orderId, BasketId $basketId, PositionArray $positions)
    {
        parent::__construct($eventId, $dateTime, $orderId);
        $this->basketId = $basketId;
        $this->positions = $positions;
    }


    /**
     * @return BasketId
     */
    public function getBasketId(): BasketId
    {
        return $this->basketId;
    }

    /**
     * @return PositionArray
     */
    public function getPositions(): PositionArray
    {
        return $this->positions;
    }


}