<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class BasketHasBeenClosedTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenClosed::__construct
     */
    public function testConstructor()
    {

        $basketId = $this->getMockBuilder(BasketId::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $basketId BasketId */

        $basketHasBeenClosed = new BasketHasBeenClosed(
            $this->generateEventId(),
            $this->generateDateTime(),
            $basketId
        );

        $this->assertInstanceOf(Event::class, $basketHasBeenClosed);
        $this->assertSameEventIdAsGenerated($basketHasBeenClosed);
        $this->assertSameDateTimeAsGenerated($basketHasBeenClosed);
        $this->assertSame($basketId, $basketHasBeenClosed->getBasketId());

    }

}
