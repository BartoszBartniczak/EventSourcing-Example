<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Event;


use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Event\Id;

class EmailHasNotBeenSent extends Event
{
    /**
     * @var string
     */
    protected $errorMessage;

    public function __construct(Id $eventId, \DateTime $dateTime, Email $email, string $errorMessage)
    {
        parent::__construct($eventId, $dateTime, $email);
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

}