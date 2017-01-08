<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event\Repository;


use BartoszBartniczak\EventSourcing\Shop\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;
use BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate;

interface EventRepository
{

    public function saveEventStream(EventStream $stream);

    public function saveEventAggregate(EventAggregate $eventAggregate);

    public function saveEvent(Event $event);

    /**
     * @param string|null $eventFamily Family name filter. If null, returns all events
     * @param array|null $parameters Additional parameters used for searching.
     * @return EventStream
     */
    public function find(string $eventFamily = null, array $parameters = null): EventStream;

}