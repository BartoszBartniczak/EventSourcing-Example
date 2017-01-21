<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Event;

use BartoszBartniczak\EventSourcing\Event\Event as BasicEvent;
use BartoszBartniczak\EventSourcing\Shop\Order\Id;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class EventTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Event\Event::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Event\Event::getEventFamilyName()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Event\Event::getOrderId()
     */
    public function testConstructor()
    {
        $orderId = new Id(uniqid());

        $event = $this->getMockBuilder(Event::class)
            ->setConstructorArgs([
                $this->generateEventId(),
                $this->generateDateTime(),
                $orderId,
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $event Event */

        $this->assertInstanceOf(BasicEvent::class, $event);
        $this->assertEquals(Event::FAMILY_NAME, $event->getEventFamilyName());
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertSame($orderId, $event->getOrderId());
    }

}
