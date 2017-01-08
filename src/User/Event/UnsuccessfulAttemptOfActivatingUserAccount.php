<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Event\Id;

class UnsuccessfulAttemptOfActivatingUserAccount extends Event
{

    /**
     * @var string
     */
    protected $activationToken;

    /**
     * @var string
     */
    protected $message;

    /**
     * @inheritDoc
     */
    public function __construct(Id $eventId, \DateTime $dateTime, string $userEmail, string $activationToken, string $message)
    {
        parent::__construct($eventId, $dateTime, $userEmail);
        $this->activationToken = $activationToken;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getActivationToken(): string
    {
        return $this->activationToken;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }


}