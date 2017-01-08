<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class BasketHasBeenClosedTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenClosed::__construct
     */
    public function testConstructor()
    {

        $basket = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basket Basket */

        $basketHasBeenClosed = new BasketHasBeenClosed(
            $this->generateEventId(),
            $this->generateDateTime(),
            $basket
        );

        $this->assertInstanceOf(Event::class, $basketHasBeenClosed);
        $this->assertSameEventIdAsGenerated($basketHasBeenClosed);
        $this->assertSameDateTimeAsGenerated($basketHasBeenClosed);
        $this->assertSame($basket, $basketHasBeenClosed->getBasket());

    }

}
