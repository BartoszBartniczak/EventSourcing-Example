<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order;


use BartoszBartniczak\EventSourcing\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Order\Event\OrderHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Order\Id as OrderId;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\PositionArray;

class Order extends EventAggregate
{

    /**
     * @var OrderId
     */
    private $orderId;

    /**
     * @var BasketId
     */
    private $basketId;

    /**
     * @var \BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\PositionArray
     */
    private $positions;

    /**
     * @return Id
     */
    public function getOrderId(): Id
    {
        return $this->orderId;
    }

    /**
     * @return BasketId
     */
    public function getBasketId(): BasketId
    {
        return $this->basketId;
    }

    /**
     * @return \BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\PositionArray
     */
    public function getPositions(): PositionArray
    {
        return $this->positions;
    }

    /**
     * @param OrderHasBeenCreated $orderHasBeenCreated
     */
    protected function handleOrderHasBeenCreated(OrderHasBeenCreated $orderHasBeenCreated)
    {
        $this->__construct($orderHasBeenCreated->getOrderId(), $orderHasBeenCreated->getBasketId(), $orderHasBeenCreated->getPositions());
    }

    /**
     * Order constructor.
     * @param Id $orderId
     * @param BasketId $basketId
     * @param \BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\PositionArray $positions
     */
    public function __construct(Id $orderId, BasketId $basketId, PositionArray $positions)
    {
        parent::__construct();
        $this->orderId = $orderId;
        $this->basketId = $basketId;
        $this->positions = $positions;
    }


}