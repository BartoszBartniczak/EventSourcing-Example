<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event;


use BartoszBartniczak\ArrayObject\ArrayOfObjects;

class EventStreamTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\EventStream::__construct
     */
    public function testConstructor()
    {

        $eventStream = new EventStream();
        $this->assertInstanceOf(ArrayOfObjects::class, $eventStream);
        $this->assertEquals(Event::class, $eventStream->getClassName());

        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event Event */

        $eventStream = new EventStream([$event]);
        $this->assertEquals(1, $eventStream->count());
    }

}
