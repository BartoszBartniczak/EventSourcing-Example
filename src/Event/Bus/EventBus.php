<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event\Bus;


use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;

/**
 * EventBus processes all the Events from the System.
 * It may be used for fraud detection, data analysis, etc.
 *
 * @package Shop\Event\Bus
 */
interface EventBus
{

    public function emmit(EventStream $eventStream);


}