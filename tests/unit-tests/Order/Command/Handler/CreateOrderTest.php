<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Command\Handler;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\CloseBasket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position as BasketPosition;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\PositionArray as BasketPositions;
use BartoszBartniczak\EventSourcing\Shop\Email\Command\SendEmail;
use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Email\Sender\Service;
use BartoszBartniczak\EventSourcing\Shop\Order\Command\CreateOrder as CreateOrderCommand;
use BartoszBartniczak\EventSourcing\Shop\Order\Event\OrderHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Order\Order;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\Position as OrderPosition;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray as OrderPositions;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;
use BartoszBartniczak\EventSourcing\UUID\Generator;
use BartoszBartniczak\EventSourcing\UUID\UUID;

class CreateOrderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Command\Handler\CreateOrder::handle
     */
    public function testHandle()
    {

        $uuid = $this->getMockBuilder(UUID::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'toNative'
            ])
            ->getMock();
        $uuid->method('toNative')
            ->willReturn('45912e69-59e8-4be4-93cb-132f0945495b');
        /* @var $uuid UUID */

        $generator = $this->getMockBuilder(Generator::class)
            ->setMethods([
                'generate'
            ])
            ->getMock();
        $generator->method('generate')
            ->willReturn($uuid);
        /* @var $generator Generator */

        $basketId = $this->getMockBuilder(BasketId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basketId BasketId */

        $product1 = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getId'
            ])
            ->getMock();
        $product1->method('getId')
            ->willReturn(new ProductId(uniqid()));
        /* @var $product1 Product */

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
            ->willReturn(120.45);
        /* @var $basketPosition BasketPosition */

        $product2 = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getId'
            ])
            ->getMock();
        $product2->method('getId')
            ->willReturn(new ProductId(uniqid()));
        /* @var $product2 Product */

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
            ->willReturn(1.12);
        /* @var $basketPosition BasketPosition */

        $basketPositions = new BasketPositions();
        $basketPositions[] = $basketPosition1;
        $basketPositions[] = $basketPosition2;

        $basket = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getId',
                'getPositions'
            ])
            ->getMock();

        $basket->method('getId')
            ->willReturn($basketId);

        $basket->method('getPositions')
            ->willReturn($basketPositions);
        /* @var $basket Basket */

        $service = $this->getMockBuilder(Service::class)
            ->getMockForAbstractClass();
        /* @var $service Service */

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */

        $createOrderCommand = new CreateOrderCommand(
            $generator,
            $basket,
            $service,
            $email);

        $createOrder = new CreateOrder($generator);
        $order = $createOrder->handle($createOrderCommand);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(0, $order->getCommittedEvents()->count());
        $this->assertEquals(1, $order->getUncommittedEvents()->count());
        $orderHasBeenCreated = $order->getUncommittedEvents()->shift();
        $this->assertInstanceOf(OrderHasBeenCreated::class, $orderHasBeenCreated);
        /* @var $orderHasBeenCreated OrderHasBeenCreated */
        $this->assertSame($uuid->toNative(), $orderHasBeenCreated->getOrderId()->toNative());
        $this->assertSame($basketId, $orderHasBeenCreated->getBasketId());
        $this->assertSamePositions($basket->getPositions(), $orderHasBeenCreated->getPositions());
        $this->assertEquals(2, $createOrder->getNextCommands()->count());
        $closeBasketCommand = $createOrder->getNextCommands()->shift();
        $this->assertInstanceOf(CloseBasket::class, $closeBasketCommand);
        /* @var $closeBasketCommand CloseBasket */
        $this->assertEquals($basket, $closeBasketCommand->getBasket());
        $sendEmailCommand = $createOrder->getNextCommands()->shift();
        $this->assertInstanceOf(SendEmail::class, $sendEmailCommand);
        /* @var $sendEmailCommand SendEmail */
        $this->assertSame($email, $sendEmailCommand->getEmail());
        $this->assertSame($service, $sendEmailCommand->getEmailSenderService());

        $this->assertEquals(0, $createOrder->getAdditionalEvents()->count());
    }

    /**
     * @param BasketPositions $basketPositions
     * @param OrderPositions $orderPositions
     */
    public function assertSamePositions(BasketPositions $basketPositions, OrderPositions $orderPositions)
    {

        $this->assertEquals($basketPositions->count(), $orderPositions->count());

        if ($basketPositions->count() > 0) {

            $basketPosition = $basketPositions->shift();
            /* @var $basketPosition BasketPosition */

            $orderPosition = $orderPositions->shift();
            /* @var $orderPosition OrderPosition */

            $this->assertSame($basketPosition->getProduct(), $orderPosition->getProduct());
            $this->assertSame($basketPosition->getQuantity(), $orderPosition->getQuantity());
        }

    }

}
