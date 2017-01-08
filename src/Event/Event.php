<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event;


abstract class Event
{

    /**
     * @var Id
     */
    protected $eventId;

    /**
     * @var \DateTime
     */
    protected $dateTime;

    /**
     * Event constructor.
     * @param Id $eventId
     * @param \DateTime $dateTime
     */
    public function __construct(Id $eventId, \DateTime $dateTime)
    {
        $this->eventId = $eventId;
        $this->dateTime = $dateTime;
    }

    /**
     * @return Id
     */
    public function getEventId(): Id
    {
        return $this->eventId;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return get_class($this);
    }

    /**
     * @return string
     */
    abstract public function getEventFamilyName(): string;


}