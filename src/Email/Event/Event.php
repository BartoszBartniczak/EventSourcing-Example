<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Event;

use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Event\Event as BasicEvent;
use BartoszBartniczak\EventSourcing\Shop\Event\Id;


abstract class Event extends BasicEvent
{

    const FAMILY_NAME = 'Email';

    /**
     * @var Email
     */
    protected $email;

    /**
     * Event constructor.
     * @param Id $eventId
     * @param \DateTime $dateTime
     * @param Email $email
     */
    public function __construct(Id $eventId, \DateTime $dateTime, Email $email)
    {
        parent::__construct($eventId, $dateTime);
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEventFamilyName(): string
    {
        return self::FAMILY_NAME;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }


}