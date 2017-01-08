<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;

use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\CreateNewBasket as CreateNewBasketCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator;

class CreateNewBasketTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\CreateNewBasket::handle
     */
    public function testHandle()
    {

        $id = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $id Id */

        $basketMock = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $id, 'user@email.com'
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $basketMock Basket */

        $generator = $this->getMockBuilder(Generator::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $generator Generator */

        $factory = $this->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'createNew'
            ])
            ->getMock();
        $factory->method('createNew')
            ->willReturn($basketMock);
        /* @var $factory Factory */

        $createNewBasketCommand = new CreateNewBasketCommand($factory, 'user@email.com');

        $createNewBasket = new CreateNewBasket($generator);
        $basket = $createNewBasket->handle($createNewBasketCommand);
        $this->assertSame($basketMock, $basket);
        $this->assertEquals(0, $basket->getCommittedEvents()->count());
        $this->assertEquals(1, $basket->getUncommittedEvents()->count());
        $this->assertInstanceOf(BasketHasBeenCreated::class, $basket->getUncommittedEvents()->shift());
    }

}
