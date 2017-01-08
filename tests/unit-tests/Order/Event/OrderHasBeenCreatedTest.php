<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\EventTestCase;
use BartoszBartniczak\EventSourcing\Shop\Order\Id;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray;

class OrderHasBeenCreatedTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Event\OrderHasBeenCreated::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Event\OrderHasBeenCreated::getOrderId()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Event\OrderHasBeenCreated::getBasketId()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Event\OrderHasBeenCreated::getPositions()
     */
    public function testGetters()
    {

        $orderId = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $orderId Id */

        $basketId = $this->getMockBuilder(BasketId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basketId BasketId */

        $positions = $this->getMockBuilder(PositionArray::class)
            ->getMock();
        /* @var $positions PositionArray */

        $orderHasBeenCreated = new OrderHasBeenCreated(
            $this->generateEventId(),
            $this->generateDateTime(),
            $orderId,
            $basketId,
            $positions
        );

        $this->assertInstanceOf(Event::class, $orderHasBeenCreated);
        $this->assertSameEventIdAsGenerated($orderHasBeenCreated);
        $this->assertSameDateTimeAsGenerated($orderHasBeenCreated);
        $this->assertSame($orderId, $orderHasBeenCreated->getOrderId());
        $this->assertSame($basketId, $orderHasBeenCreated->getBasketId());
        $this->assertSame($positions, $orderHasBeenCreated->getPositions());
    }

}
