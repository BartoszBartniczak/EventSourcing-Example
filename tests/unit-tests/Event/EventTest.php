<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event;


use BartoszBartniczak\EventSourcing\Shop\EventTestCase;

class EventTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Event::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Event::getEventId
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Event::getDateTime
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Event::getName
     */
    public function testGetters()
    {

        $event = $this->getMockBuilder(Event::class)
            ->setConstructorArgs([
                $this->generateEventId(),
                $this->generateDateTime()
            ])
            ->setMockClassName('EventMock')
            ->getMockForAbstractClass();
        /* @var $event \Shop\Event\Event */

        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertEquals('EventMock', $event->getName());
    }

}
