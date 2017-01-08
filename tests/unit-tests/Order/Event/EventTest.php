<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Event;

use BartoszBartniczak\EventSourcing\Shop\Event\Event as BasicEvent;

class EventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Event\Event::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Event\Event::getEventFamilyName()
     */
    public function testConstructor()
    {

        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $event Event */

        $this->assertInstanceOf(BasicEvent::class, $event);
        $this->assertEquals(Event::FAMILY_NAME, $event->getEventFamilyName());
    }

}
