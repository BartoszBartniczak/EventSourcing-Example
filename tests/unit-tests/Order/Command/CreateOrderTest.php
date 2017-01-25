<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Command;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Email\Sender\Service;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\Factory;
use BartoszBartniczak\EventSourcing\UUID\Generator;

class CreateOrderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Command\CreateOrder::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Command\CreateOrder::getUuidGenerator()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Command\CreateOrder::getBasket()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Command\CreateOrder::getEmailSenderService()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Command\CreateOrder::getEmail
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Command\CreateOrder::getPositionsFactory
     */
    public function testGetters()
    {

        $generator = $this->getMockBuilder(Generator::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $generator Generator */

        $basket = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basket Basket */

        $service = $this->getMockBuilder(Service::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $service Service */

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */

        $factory = $this->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $factory Factory */

        $createOrder = new CreateOrder($generator, $basket, $service, $email, $factory);
        $this->assertSame($generator, $createOrder->getUuidGenerator());
        $this->assertSame($basket, $createOrder->getBasket());
        $this->assertSame($service, $createOrder->getEmailSenderService());
        $this->assertSame($email, $createOrder->getEmail());
        $this->assertSame($factory, $createOrder->getPositionsFactory());
    }

}
