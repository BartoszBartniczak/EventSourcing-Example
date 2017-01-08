<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event;


use BartoszBartniczak\EventSourcing\Shop\Event\Event as BasicEvent;

class EventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event\Event::getEventFamilyName
     */
    public function testGetEventFamilyName()
    {

        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMockForAbstractClass();
        /* @var $event Event */

        $this->assertInstanceOf(BasicEvent::class, $event);
        $this->assertSame(Event::FAMILY_NAME, $event->getEventFamilyName());
    }

}
