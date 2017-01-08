<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;

use BartoszBartniczak\EventSourcing\Shop\Event\Event as BasicEvent;
use BartoszBartniczak\EventSourcing\Shop\Event\Id;
use BartoszBartniczak\EventSourcing\Shop\User\User;


abstract class Event extends BasicEvent
{
    const FAMILY_NAME = 'User';

    /**
     * @var User
     */
    protected $userEmail;

    public function __construct(Id $eventId, \DateTime $dateTime, string $userEmail)
    {
        parent::__construct($eventId, $dateTime);
        $this->userEmail = $userEmail;
    }

    /**
     * @inheritDoc
     */
    public function getEventFamilyName(): string
    {
        return self::FAMILY_NAME;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }


}