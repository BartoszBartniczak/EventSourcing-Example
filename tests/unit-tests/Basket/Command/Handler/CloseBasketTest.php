<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\CloseBasket as CloseBasketCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenClosed;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\UUID\Generator;

class CloseBasketTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\CloseBasket::handle
     */
    public function testHandle()
    {

        $generator = $this->getMockBuilder(Generator::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $generator Generator */

        $basketId = $this->getMockBuilder(BasketId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basketId BasketId */

        $basketMock = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $basketId,
                'owner@email.com'
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $basketMock Basket */

        $closeBasketCommand = new CloseBasketCommand($basketMock);

        $closeBasket = new CloseBasket($generator);
        $basket = $closeBasket->handle($closeBasketCommand);

        $this->assertSame($basketMock, $basket);
        $this->assertEquals(0, $basket->getCommittedEvents()->count());
        $this->assertEquals(1, $basket->getUncommittedEvents()->count());
        $basketHasBeenClosed = $basket->getUncommittedEvents()->shift();
        $this->assertInstanceOf(BasketHasBeenClosed::class, $basketHasBeenClosed);
        /* @var $basketHasBeenClosed BasketHasBeenClosed */
        $this->assertSame($basket, $basketHasBeenClosed->getBasket());
    }

}
