<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Factory;


use BartoszBartniczak\EventSourcing\Shop\Order\Order;
use BartoszBartniczak\EventSourcing\UUID\Generator;
use BartoszBartniczak\EventSourcing\UUID\UUID;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Factory\Factory::createEmpty
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Factory\Factory::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Factory\Factory::generateNewId
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Factory\Factory::generateEmptyBasketId
     */
    public function testCreateEmpty()
    {

        $generator = $this->getMockBuilder(Generator::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'generate'
            ])
            ->getMock();
        $generator->method('generate')
            ->willReturn(new UUID('b26c245b-00ce-4465-bc60-5318fbd3868e'));
        /* @var $generator Generator */

        $factory = new Factory($generator);
        $order = $factory->createEmpty();
        $this->assertInstanceOf(Order::class, $order);
        $this->assertSame('b26c245b-00ce-4465-bc60-5318fbd3868e', $order->getOrderId()->toNative());
        $this->assertSame('', $order->getBasketId()->toNative());
        $this->assertEquals(0, $order->getPositions()->count());
    }

}
