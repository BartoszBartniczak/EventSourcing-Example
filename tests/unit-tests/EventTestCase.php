<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop;


use BartoszBartniczak\EventSourcing\Shop\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Event\Id;

abstract class EventTestCase extends TestCase
{

    /**
     * @var Id
     */
    private $eventId;
    /**
     * @var
     */
    private $dateTime;

    public function assertSameEventIdAsGenerated(Event $event)
    {
        $this->assertSame($this->eventId, $event->getEventId());
    }

    public function assertSameDateTimeAsGenerated(Event $event)
    {
        $this->assertSame($this->dateTime, $event->getDateTime());
    }

    protected function generateEventId(): Id
    {
        $this->eventId = new Id(uniqid());
        return $this->eventId;
    }

    protected function generateDateTime(): \DateTime
    {
        $this->dateTime = new \DateTime();
        return $this->dateTime;
    }

}