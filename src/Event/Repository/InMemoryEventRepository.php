<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event\Repository;


use BartoszBartniczak\ArrayObject\ArrayObject;
use BartoszBartniczak\EventSourcing\Shop\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;
use BartoszBartniczak\EventSourcing\Shop\Event\Serializer\Serializer as EventSerializer;
use BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate;

class InMemoryEventRepository implements EventRepository
{
    /**
     * @var ArrayObject
     */
    protected $memory;

    /**
     * @var EventSerializer
     */
    protected $eventSerializer;

    /**
     * InMemoryEventRepository constructor.
     * @param EventSerializer $serializer
     */
    public function __construct(EventSerializer $serializer)
    {
        $this->memory = new ArrayObject();
        $this->eventSerializer = $serializer;
    }

    public function saveEventAggregate(EventAggregate $eventAggregate)
    {
        $this->saveEventStream($eventAggregate->getUncommittedEvents());
        $eventAggregate->commit();
    }

    public function saveEventStream(EventStream $stream)
    {
        foreach ($stream as $event) {
            $this->saveEvent($event);
        }
    }

    public function saveEvent(Event $event)
    {
        $serializedData = $this->eventSerializer->serialize($event);
        $this->memory[] = $serializedData;
    }

    /**
     * @param string|null $eventFamily Family name filter. If null, returns all events
     * @param array|null $parameters Additional parameters used for searching. In case of InMemoryEventRepository class the parameters array should be like: [$key=>$callback]. The $key is just a key, it does not affect on results and does not has to be set. The $callback is a function which is used to filter data, using ArrayObject::filter method. NOTICE: Data passed to the function is serialized event. You can use Serializer to deserialize the object or use the json_decode method to treat the event as array.
     * @return EventStream
     * @throws \UnexpectedValueException when json array does not contain eventFamilyName property
     * @throws \UnexpectedValueException if the $callback in $parameters array is not a function
     */
    public function find(string $eventFamily = null, array $parameters = null): EventStream
    {
        if (!is_array($parameters)) {
            $parameters = [];
        }

        if (!empty($eventFamily)) {
            $filteredData = $this->memory->filter(
                function ($serializedEvent) use ($eventFamily) {
                    $eventArray = json_decode($serializedEvent, true);
                    if (!isset($eventArray[$this->eventSerializer->getPropertyKey('eventFamilyName')])) {
                        throw new \UnexpectedValueException('Event data expected.');
                    }
                    return $eventArray[$this->eventSerializer->getPropertyKey('eventFamilyName')] === $eventFamily;
                }
            );
        } else {
            $filteredData = $this->memory;
        }

        foreach ($parameters as $filter) {
            if (!is_callable($filter)) {
                throw new \UnexpectedValueException('$callback have to be function!');
            } else {
                $filteredData = $filteredData->filter($filter);
            }
        }

        return $this->deserializeEvents($filteredData);
    }

    /**
     * @param ArrayObject $serializedEvents
     * @return EventStream
     */
    private function deserializeEvents(ArrayObject $serializedEvents): EventStream
    {
        $events = [];
        foreach ($serializedEvents as $serializedEvent) {
            $events[] = $this->eventSerializer->deserialize($serializedEvent);
        }
        return new EventStream($events);
    }

    /**
     * @return EventSerializer
     */
    public function getEventSerializer(): EventSerializer
    {
        return $this->eventSerializer;
    }

}