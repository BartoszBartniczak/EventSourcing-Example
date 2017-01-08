<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order;

use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position as BasketPosition;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\PositionArray as BasketPositions;
use BartoszBartniczak\EventSourcing\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\Order\Event\OrderHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;

class OrderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Order::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Order::getOrderId()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Order::getBasketId()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Order::getPositions()
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

        $order = new Order($orderId, $basketId);
        $this->assertInstanceOf(EventAggregate::class, $order);
        $this->assertEquals(0, $order->getCommittedEvents()->count());
        $this->assertEquals(0, $order->getUncommittedEvents()->count());
        $this->assertSame($orderId, $order->getOrderId());
        $this->assertSame($basketId, $order->getBasketId());
        $this->assertEquals(0, $order->getPositions()->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Order::addPositionsFromBasket
     */
    public function testAddPositionsFromBasket()
    {
        $orderId = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $orderId Id */

        $basketId = $this->getMockBuilder(BasketId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basketId BasketId */

        $product1 = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $product1 Product */

        $product2 = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $product2 Product */

        $basketPosition1 = $this->getMockBuilder(BasketPosition::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getProduct',
                'getQuantity'
            ])
            ->getMock();
        $basketPosition1->method('getProduct')
            ->willReturn($product1);
        $basketPosition1->method('getQuantity')
            ->willReturn(120.01);
        /* @var $basketPosition1 BasketPosition */

        $basketPosition2 = $this->getMockBuilder(BasketPosition::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getProduct',
                'getQuantity'
            ])
            ->getMock();
        $basketPosition2->method('getProduct')
            ->willReturn($product2);
        $basketPosition2->method('getQuantity')
            ->willReturn(13.07);
        /* @var $basketPosition1 BasketPosition */

        $basketPositions = new BasketPositions();
        $basketPositions[] = $basketPosition1;
        $basketPositions[] = $basketPosition2;

        $order = new Order($orderId, $basketId);
        $order->addPositionsFromBasket($basketPositions);
        $this->assertEquals(2, $order->getPositions()->count());

        $position1 = $order->getPositions()->shift();
        /* @var $position1 \Shop\Order\Position\Position */
        $this->assertSame($product1, $position1->getProduct());
        $this->assertSame(120.01, $position1->getQuantity());

        $position2 = $order->getPositions()->shift();
        /* @var $position2 \Shop\Order\Position\Position */
        $this->assertSame($product2, $position2->getProduct());
        $this->assertSame(13.07, $position2->getQuantity());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Order::handleOrderHasBeenCreated
     */
    public function testHandleOrderHasBeenCreated()
    {

        $orderIdConstructor = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $orderId Id */

        $basketIdConstructor = $this->getMockBuilder(BasketId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basketId BasketId */

        $order = $this->getMockBuilder(Order::class)
            ->setConstructorArgs([
                $orderIdConstructor, $basketIdConstructor
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $order->method('findHandleMethod')
            ->willReturn('handleOrderHasBeenCreated');
        /* @var $order Order */

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

        $orderHasBeenCreatedEvent = $this->getMockBuilder(OrderHasBeenCreated::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getOrderId',
                'getBasketId',
                'getPositions'
            ])
            ->getMock();
        $orderHasBeenCreatedEvent->method('getOrderId')
            ->willReturn($orderId);
        $orderHasBeenCreatedEvent->method('getBasketId')
            ->willReturn($basketId);
        $orderHasBeenCreatedEvent->method('getPositions')
            ->willReturn($positions);
        /* @var $orderHasBeenCreatedEvent OrderHasBeenCreated */

        $order->apply($orderHasBeenCreatedEvent);
        $this->assertEquals(0, $order->getCommittedEvents()->count());
        $this->assertEquals(1, $order->getUncommittedEvents()->count());
        $orderHasBeenCreated = $order->getUncommittedEvents()->shift();
        $this->assertSame($orderHasBeenCreatedEvent, $orderHasBeenCreated);
        $this->assertSame($orderId, $order->getOrderId());
        $this->assertSame($basketId, $order->getBasketId());
        $this->assertSame($positions, $order->getPositions());
    }

}
