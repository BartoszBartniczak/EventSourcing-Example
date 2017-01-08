<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event\Repository;


use BartoszBartniczak\EventSourcing\Shop\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;
use BartoszBartniczak\EventSourcing\Shop\Event\Serializer\Serializer;
use BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate;


class InMemoryEventRepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository::getEventSerializer
     */
    public function testConstructor()
    {
        $eventSerializer = $this->getMockBuilder(Serializer::class)
            ->setMethods([
                'serialize',
                'deserialize'
            ])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $eventSerializer->expects($this->never())
            ->method('deserialize');

        /* @var $eventSerializer Serializer */

        $inMemoryEventRepository = new InMemoryEventRepository($eventSerializer);
        $this->assertInstanceOf(EventRepository::class, $inMemoryEventRepository);
        $this->assertSame($eventSerializer, $inMemoryEventRepository->getEventSerializer());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository::saveEvent
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository::find
     */
    public function testSaveEvent()
    {
        $event1 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event1 Event */
        $event2 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event2 Event */

        $eventSerializer = $this->getMockBuilder(Serializer::class)
            ->setMethods([
                'serialize',
                'deserialize'
            ])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $eventSerializer->expects($this->exactly(2))
            ->method('serialize')
            ->willReturnMap([
                [$event1, '{"name":"Event1"}'],
                [$event2, '{"name":"Event2"}']
            ]);

        $eventSerializer->expects(($this->exactly(2)))
            ->method('deserialize')
            ->willReturnMap([
                ['{"name":"Event1"}', $event1],
                ['{"name":"Event2"}', $event2]
            ]);
        /* @var $eventSerializer Serializer */

        $inMemoryEventRepository = new InMemoryEventRepository($eventSerializer);
        $inMemoryEventRepository->saveEvent($event1);
        $inMemoryEventRepository->saveEvent($event2);

        $events = $inMemoryEventRepository->find();

        $this->assertEquals(2, $events->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository::saveEventStream
     */
    public function testSaveEventStream()
    {
        $event1 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event1 Event */
        $event2 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event2 Event */
        $eventStream = new EventStream([$event1, $event2]);

        $eventSerializer = $this->getMockBuilder(Serializer::class)
            ->setMethods([
                'serialize',
                'deserialize'
            ])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $eventSerializer Serializer */

        $inMemoryEventRepository = $this->getMockBuilder(InMemoryEventRepository::class)
            ->setConstructorArgs([
                $eventSerializer
            ])
            ->setMethods(['saveEvent'])
            ->getMock();
        $inMemoryEventRepository->expects($this->exactly(2))
            ->method('saveEvent');
        /* @var $inMemoryEventRepository InMemoryEventRepository */


        $inMemoryEventRepository->saveEventStream($eventStream);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository::saveEventAggregate
     */
    public function testSaveEventAggregate()
    {
        $eventAggregate = $this->getMockBuilder(EventAggregate::class)
            ->getMock();
        /* @var $eventAggregate EventAggregate */


        $eventSerializer = $this->getMockBuilder(Serializer::class)
            ->setMethods([
                'serialize',
                'deserialize'
            ])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $eventSerializer Serializer */

        $inMemoryEventRepository = $this->getMockBuilder(InMemoryEventRepository::class)
            ->setConstructorArgs([
                $eventSerializer
            ])
            ->setMethods(['saveEventStream'])
            ->getMock();

        $inMemoryEventRepository->expects($this->exactly(1))
            ->method('saveEventStream')
            ->with($eventAggregate->getUncommittedEvents());
        /* @var $inMemoryEventRepository InMemoryEventRepository */

        $inMemoryEventRepository->saveEventAggregate($eventAggregate);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository::saveEvent
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository::find
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository::deserializeEvents
     */
    public function testFind()
    {
        $event1 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event1 Event */
        $event2 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event2 Event */

        $eventSerializer = $this->getMockBuilder(Serializer::class)
            ->setMethods([
                'serialize',
                'deserialize',
                'getPropertyKey'
            ])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $eventSerializer->method("getPropertyKey")
            ->willReturn('event_family');

        $eventSerializer->expects($this->exactly(2))
            ->method('serialize')
            ->willReturnMap([
                [$event1, '{"name":"Event1", "event_family":"Family1"}'],
                [$event2, '{"name":"Event2", "event_family":"Family2"}']
            ]);

        $eventSerializer->method('deserialize')
            ->willReturnMap([
                ['{"name":"Event1", "event_family":"Family1"}', $event1],
                ['{"name":"Event2", "event_family":"Family2"}', $event2]
            ]);
        /* @var $eventSerializer Serializer */

        $inMemoryEventRepository = new InMemoryEventRepository($eventSerializer);
        $inMemoryEventRepository->saveEvent($event1);
        $inMemoryEventRepository->saveEvent($event2);

        $events = $inMemoryEventRepository->find();

        $this->assertInstanceOf(EventStream::class, $events);
        $this->assertEquals(2, $events->count());

        $events = $inMemoryEventRepository->find("Family1");

        $this->assertInstanceOf(EventStream::class, $events);
        $this->assertEquals(1, $events->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository::find
     */
    public function testFindThrowsExcptionIfDataDoesNotContainEventFamilyName()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Event data expected.');

        $event1 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event1 Event */
        $event2 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event2 Event */

        $eventSerializer = $this->getMockBuilder(Serializer::class)
            ->setMethods([
                'serialize',
                'deserialize',
                'getEventFamilyPropertyKey'
            ])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $eventSerializer->method("getEventFamilyPropertyKey")
            ->willReturn('event_family');

        $eventSerializer->expects($this->exactly(2))
            ->method('serialize')
            ->willReturnMap([
                [$event1, '{"name":"Event1"}'],
                [$event2, '{"name":"Event2"}']
            ]);

        $eventSerializer->method('deserialize')
            ->willReturnMap([
                ['{"name":"Event1"}', $event1],
                ['{"name":"Event2"}', $event2]
            ]);
        /* @var $eventSerializer Serializer */

        $inMemoryEventRepository = new InMemoryEventRepository($eventSerializer);
        $inMemoryEventRepository->saveEvent($event1);
        $inMemoryEventRepository->saveEvent($event2);


        $inMemoryEventRepository->find("Family1");
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository::find
     */
    public function testFindUsesParameters()
    {
        $event1 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event1 Event */
        $event2 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event2 Event */

        $eventSerializer = $this->getMockBuilder(Serializer::class)
            ->setMethods([
                'serialize',
                'deserialize',
                'getPropertyKey'
            ])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $eventSerializer->method("getPropertyKey")
            ->willReturn('event_family');

        $eventSerializer->expects($this->exactly(2))
            ->method('serialize')
            ->willReturnMap([
                [$event1, '{"name":"Event1", "event_family":"Family1", "id": 100}'],
                [$event2, '{"name":"Event2", "event_family":"Family1", "id": 200}']
            ]);

        $eventSerializer->method('deserialize')
            ->willReturnMap([
                ['{"name":"Event1", "event_family":"Family1", "id": 100}', $event1],
                ['{"name":"Event2", "event_family":"Family1", "id": 200}', $event2]
            ]);
        /* @var $eventSerializer Serializer */

        $inMemoryEventRepository = new InMemoryEventRepository($eventSerializer);
        $inMemoryEventRepository->saveEvent($event1);
        $inMemoryEventRepository->saveEvent($event2);

        $events = $inMemoryEventRepository->find("Family1", ['id' => function ($serializedEvent) {
            $eventArray = json_decode($serializedEvent, true);
            if (!isset($eventArray['id'])) {
                return false;
            }

            if ($eventArray['id'] < 200) {
                return true;
            }
        }]);

        $this->assertInstanceOf(EventStream::class, $events);
        $this->assertEquals(1, $events->count());
        $this->assertSame($event1, $events->pop());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository::find
     */
    public function testFindThrowsUnexpectedValueExceptionIfParameterIsNotACallback()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('$callback have to be function!');

        $event1 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event1 Event */
        $event2 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event2 Event */

        $eventSerializer = $this->getMockBuilder(Serializer::class)
            ->setMethods([
                'serialize',
                'deserialize',
                'getPropertyKey'
            ])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $eventSerializer->method("getPropertyKey")
            ->willReturn('event_family');

        $eventSerializer->expects($this->exactly(2))
            ->method('serialize')
            ->willReturnMap([
                [$event1, '{"name":"Event1", "event_family":"Family1", "id": 100}'],
                [$event2, '{"name":"Event2", "event_family":"Family1", "id": 200}']
            ]);

        $eventSerializer->method('deserialize')
            ->willReturnMap([
                ['{"name":"Event1", "event_family":"Family1", "id": 100}', $event1],
                ['{"name":"Event2", "event_family":"Family1", "id": 200}', $event2]
            ]);
        /* @var $eventSerializer Serializer */

        $inMemoryEventRepository = new InMemoryEventRepository($eventSerializer);
        $inMemoryEventRepository->saveEvent($event1);
        $inMemoryEventRepository->saveEvent($event2);

        $events = $inMemoryEventRepository->find("Family1", ['id']);
    }

}
