<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class EventTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\Event::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\Event::getEventFamilyName
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\Event::getBasketId
     */
    public function testGetters()
    {

        $basketId = $this->getMockBuilder(BasketId::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $basketId BasketId */

        $event = $this->getMockBuilder(Event::class)
            ->setConstructorArgs([
                $this->generateEventId(),
                $this->generateDateTime(),
                $basketId
            ])
            ->getMockForAbstractClass();
        /* @var $event Event */

        $this->assertSame($basketId, $event->getBasketId());
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertSame(Event::FAMILY_NAME, $event->getEventFamilyName());
    }

}
