<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event\Bus;


use BartoszBartniczak\EventSourcing\Shop\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;
use BartoszBartniczak\EventSourcing\Shop\Event\Id;

class DummyEvent extends Event
{

    static $counter;

    public function __construct()
    {
        parent::__construct(new Id(uniqid()), new \DateTime());
        if (self::$counter === null) {
            self::$counter = 0;
        }
    }

    /**
     * @inheritDoc
     */
    public function getEventFamilyName(): string
    {
        return 'DummyEvent';
    }

    public function count(): int
    {
        return self::$counter;
    }

    public function increment()
    {
        self::$counter++;
    }

}

class SimpleEventBusTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Bus\SimpleEventBus::__construct
     */
    public function testConstructor()
    {
        $simpleEventBus = new SimpleEventBus();
        $this->assertInstanceOf(EventBus::class, $simpleEventBus);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Bus\SimpleEventBus::emmit
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Bus\SimpleEventBus::registerHandler
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Bus\SimpleEventBus::handle
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Bus\SimpleEventBus::findHandleMethod
     */
    public function testEmmit()
    {

        $event1 = new DummyEvent();
        $event2 = new DummyEvent();

        $eventStream = new EventStream();
        $eventStream->append($event1);
        $eventStream->append($event2);

        $simpleEventBus = new SimpleEventBus();

        $simpleEventBus->registerHandler(DummyEvent::class, function (DummyEvent $dummyEvent) {
            $dummyEvent->increment();
        });
        $simpleEventBus->emmit($eventStream);

        $this->assertEquals(2, DummyEvent::$counter);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Bus\SimpleEventBus::handle
     * @covers \BartoszBartniczak\EventSourcing\Shop\Event\Bus\SimpleEventBus::findHandleMethod
     */
    public function testEmmitDoesNotThrowExceptionIfHandlerIsNotRegistered()
    {
        $eventStream = new EventStream([new DummyEvent()]);
        $simpleEventBus = new SimpleEventBus();
        $simpleEventBus->emmit($eventStream);
    }

}
