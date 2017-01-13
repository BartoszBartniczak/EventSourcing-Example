<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Event;

use BartoszBartniczak\EventSourcing\Event\Event as BasicEvent;
use BartoszBartniczak\EventSourcing\Event\Id;
use BartoszBartniczak\EventSourcing\Shop\Order\Id as OrderId;

abstract class Event extends BasicEvent
{

    const FAMILY_NAME = 'Order';

    /**
     * @var OrderId
     */
    protected $orderId;

    public function __construct(Id $eventId, \DateTime $dateTime, OrderId $orderId)
    {
        parent::__construct($eventId, $dateTime);
        $this->orderId = $orderId;
    }

    /**
     * @inheritDoc
     */
    public function getEventFamilyName(): string
    {
        return self::FAMILY_NAME;
    }

    /**
     * @return OrderId
     */
    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }


}