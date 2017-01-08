<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event;

use BartoszBartniczak\EventSourcing\Event\Event as BasicEvent;

abstract class Event extends BasicEvent
{

    const FAMILY_NAME = 'ProductRepository';

    /**
     * @inheritDoc
     */
    public function getEventFamilyName(): string
    {
        return self::FAMILY_NAME;
    }

}