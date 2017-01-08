<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class EventTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\Event::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\Event::getEventFamilyName
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\Event::getBasket
     */
    public function testGetters()
    {

        $basket = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basket Basket */

        $event = $this->getMockBuilder(Event::class)
            ->setConstructorArgs([
                $this->generateEventId(),
                $this->generateDateTime(),
                $basket
            ])
            ->getMockForAbstractClass();
        /* @var $event Event */

        $this->assertSame($basket, $event->getBasket());
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertSame(Event::FAMILY_NAME, $event->getEventFamilyName());
    }

}
