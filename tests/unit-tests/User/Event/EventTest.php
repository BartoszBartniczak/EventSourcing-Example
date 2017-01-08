<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Shop\Event\Event as BasicEvent;
use BartoszBartniczak\EventSourcing\Shop\EventTestCase;

class EventTest extends EventTestCase
{
    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Event\Event::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Event\Event::getEventFamilyName
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Event\Event::getUserEmail
     */
    public function testGetters()
    {


        $event = $this->getMockBuilder(Event::class)
            ->setConstructorArgs(
                [
                    $this->generateEventId(),
                    $this->generateDateTime(),
                    'user@company.com'
                ]
            )
            ->getMockForAbstractClass();
        /* @var $event \Shop\User\Event\Event */

        $this->assertInstanceOf(BasicEvent::class, $event);
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertEquals(Event::FAMILY_NAME, $event->getEventFamilyName());
        $this->assertEquals('user@company.com', $event->getUserEmail());
    }

}
