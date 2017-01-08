<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Shop\Event\Id;

class UserHasBeenRegistered extends Event
{
    /**
     * @var string
     */
    private $passwordHash;

    /**
     * UserHasBeenRegistered constructor.
     * @param Id $eventId
     * @param \DateTime $dateTime
     * @param string $userEmail
     * @param string $passwordHash
     * @param string $passwordSalt
     */
    public function __construct(Id $eventId, \DateTime $dateTime, string $userEmail, string $passwordHash)
    {
        parent::__construct($eventId, $dateTime, $userEmail);
        $this->passwordHash = $passwordHash;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

}