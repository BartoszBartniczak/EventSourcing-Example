<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class BasketHasBeenCreatedTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenCreated::__construct
     */
    public function testConstructor()
    {
        $basket = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basket Basket */

        $basketHasBeenCreated = new BasketHasBeenCreated(
            $this->generateEventId(),
            $this->generateDateTime(),
            $basket
        );
        $this->assertInstanceOf(Event::class, $basketHasBeenCreated);
        $this->assertSameEventIdAsGenerated($basketHasBeenCreated);
        $this->assertSameDateTimeAsGenerated($basketHasBeenCreated);
        $this->assertSame($basket, $basketHasBeenCreated->getBasket());
    }

}
