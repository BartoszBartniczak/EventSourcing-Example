<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Event\Id;
use BartoszBartniczak\EventSourcing\Event\Event as BasicEvent;

abstract class Event extends BasicEvent
{

    const FAMILY_NAME = 'Basket';

    /**
     * @var Basket
     */
    protected $basket;

    public function __construct(Id $eventId, \DateTime $dateTime, Basket $basket)
    {
        parent::__construct($eventId, $dateTime);
        $this->basket = $basket;
    }

    /**
     * @inheritDoc
     */
    public function getEventFamilyName(): string
    {
        return self::FAMILY_NAME;
    }

    /**
     * @return Basket
     */
    public function getBasket(): Basket
    {
        return $this->basket;
    }

}