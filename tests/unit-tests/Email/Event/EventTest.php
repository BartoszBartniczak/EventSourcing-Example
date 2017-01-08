<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Event;


use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Event\Event as BasicEvent;
use BartoszBartniczak\EventSourcing\Shop\EventTestCase;

class EventTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Event\Event::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Event\Event::getEventFamilyName()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Event\Event::getEmail()
     */
    public function testGetters()
    {

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */

        $event = $this->getMockBuilder(Event::class)
            ->setConstructorArgs([
                $this->generateEventId(),
                $this->generateDateTime(),
                $email
            ])
            ->setMethods(null)
            ->getMockForAbstractClass();
        /* @var $event Event */

        $this->assertInstanceOf(BasicEvent::class, $event);
        $this->assertEquals(Event::FAMILY_NAME, $event->getEventFamilyName());
        $this->assertSame($email, $event->getEmail());
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
    }

}
