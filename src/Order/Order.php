<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order;


use BartoszBartniczak\EventSourcing\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position as BasketPosition;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\PositionArray as BasketPositions;
use BartoszBartniczak\EventSourcing\Shop\Order\Event\OrderHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Order\Id as OrderId;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\Position;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray;

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
     * @var PositionArray
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
     * @return PositionArray
     */
    public function getPositions(): PositionArray
    {
        return $this->positions;
    }

    /**
     * @param BasketPositions $basketPositions
     */
    public function addPositionsFromBasket(BasketPositions $basketPositions)
    {
        foreach ($basketPositions as $basketPosition) {
            /* @var $basketPosition BasketPosition */
            $orderPosition = new Position($basketPosition->getProduct(), $basketPosition->getQuantity());
            $this->positions[$orderPosition->getProduct()->getId()->toNative()] = $orderPosition;
        }
    }

    /**
     * @param OrderHasBeenCreated $orderHasBeenCreated
     */
    protected function handleOrderHasBeenCreated(OrderHasBeenCreated $orderHasBeenCreated)
    {
        $this->__construct($orderHasBeenCreated->getOrderId(), $orderHasBeenCreated->getBasketId());
        $this->positions = $orderHasBeenCreated->getPositions();
    }

    /**
     * Order constructor.
     * @param Id $orderId
     * @param BasketId $basketId
     */
    public function __construct(Id $orderId, BasketId $basketId)
    {
        parent::__construct();
        $this->orderId = $orderId;
        $this->basketId = $basketId;
        $this->positions = new PositionArray();
    }

}