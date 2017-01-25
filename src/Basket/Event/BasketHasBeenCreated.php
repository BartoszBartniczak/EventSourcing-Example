<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Event\Id;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;

class BasketHasBeenCreated extends Event
{
    /**
     * @var string
     */
    protected $ownerEmail;

    /**
     * BasketHasBeenCreated constructor.
     * @param Id $eventId
     * @param \DateTime $dateTime
     * @param BasketId $basketId
     * @param string $ownerEmail
     */
    public function __construct(Id $eventId, \DateTime $dateTime, BasketId $basketId, string $ownerEmail)
    {
        parent::__construct($eventId, $dateTime, $basketId);
        $this->ownerEmail = $ownerEmail;
    }

    /**
     * @return string
     */
    public function getOwnerEmail(): string
    {
        return $this->ownerEmail;
    }

}