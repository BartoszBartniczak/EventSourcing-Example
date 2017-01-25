<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Event\Event as BasicEvent;
use BartoszBartniczak\EventSourcing\Event\Id;
use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;

abstract class Event extends BasicEvent
{

    const FAMILY_NAME = 'Basket';

    /**
     * @var BasketId
     */
    protected $basketId;

    public function __construct(Id $eventId, \DateTime $dateTime, BasketId $basketIdId)
    {
        parent::__construct($eventId, $dateTime);
        $this->basketId = $basketIdId;
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
    public function getBasketId(): BasketId
    {
        return $this->basketId;
    }

}