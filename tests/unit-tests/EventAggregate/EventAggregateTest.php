<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\EventAggregate;


use BartoszBartniczak\EventSourcing\Shop\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;

class EventAggregateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::getCommittedEvents
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::getUncommittedEvents
     */
    public function testConstructor()
    {

        $eventAggregate = $this->getMockBuilder(EventAggregate::class)
            ->getMockForAbstractClass();
        /* @var $eventAggregate EventAggregate */

        $this->assertEquals(0, $eventAggregate->getCommittedEvents()->count());
        $this->assertEquals(0, $eventAggregate->getUncommittedEvents()->count());
    }


    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::apply
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::handle
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::getUncommittedEvents
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::commit
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::getCommittedEvents
     */
    public function testApply()
    {

        $event1 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->setMockClassName('Event')
            ->getMock();
        /* @var $event1 Event */

        $event2 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->setMockClassName('Event')
            ->getMock();
        /* @var $event2 Event */

        $eventAggregate = $this->getMockBuilder(EventAggregate::class)
            ->setMethods([
                'findHandleMethod',
                'handleEvent'
            ])
            ->getMockForAbstractClass();

        $eventAggregate->method('findHandleMethod')
            ->willReturn('handleEvent');

        $eventAggregate->expects($this->exactly(2))
            ->method('handleEvent');
        /* @var $eventAggregate EventAggregate */

        $eventAggregate->apply($event1);
        $eventAggregate->apply($event2);

        $this->assertEquals(2, $eventAggregate->getUncommittedEvents()->count());
        $this->assertEquals(0, $eventAggregate->getCommittedEvents()->count());

        $eventAggregate->commit();
        $this->assertEquals(0, $eventAggregate->getUncommittedEvents()->count());
        $this->assertEquals(2, $eventAggregate->getCommittedEvents()->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::handle
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::findHandleMethod
     */
    public function testHandleThrowsCannotHandleTheEventException()
    {
        $this->expectException(CannotHandleTheEventException::class);
        $this->expectExceptionMessage("Method 'handleMyEvent' does not exists.");

        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->setMockClassName('MyEvent')
            ->getMock();
        /* @var $event Event */

        $eventAggregate = $this->getMockBuilder(EventAggregate::class)
            ->getMockForAbstractClass();
        /* @var $eventAggregate EventAggregate */

        $eventAggregate->apply($event);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::applyEventStream
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::handle
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::getUncommittedEvents
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::commit
     * @covers \BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate::getCommittedEvents
     */
    public function testApplyEventStream()
    {

        $event1 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->setMockClassName('Event')
            ->getMock();
        /* @var $event1 Event */

        $event2 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->setMockClassName('Event')
            ->getMock();
        /* @var $event2 Event */
        $eventStream = new EventStream([$event1, $event2]);

        $eventAggregate = $this->getMockBuilder(EventAggregate::class)
            ->setMethods([
                'findHandleMethod',
                'handleEvent'
            ])
            ->getMockForAbstractClass();

        $eventAggregate->method('findHandleMethod')
            ->willReturn('handleEvent');

        $eventAggregate->expects($this->exactly(2))
            ->method('handleEvent');
        /* @var $eventAggregate EventAggregate */

        $eventAggregate->applyEventStream($eventStream);

        $this->assertEquals(2, $eventAggregate->getUncommittedEvents()->count());
        $this->assertEquals(0, $eventAggregate->getCommittedEvents()->count());

        $eventAggregate->commit();
        $this->assertEquals(0, $eventAggregate->getUncommittedEvents()->count());
        $this->assertEquals(2, $eventAggregate->getCommittedEvents()->count());
    }

}
