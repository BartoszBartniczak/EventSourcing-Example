<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class BasketHasBeenClosedTest extends SerializationTestCase
{
    protected function getJsonFileName(): string
    {
        return 'BasketHasBeenClosed.json';
    }

    protected function getEvent(): Event
    {
        return new BasketHasBeenClosed(
            $this->generateEventId('5c230fd5-3050-4635-a611-ca99865f36a2'),
            $this->generateDateTime('2017-01-26T08:51:10+0100'),
            new BasketId('b70fce84-2a90-46e4-9867-e6b2574aadd9')
        );
    }


}
