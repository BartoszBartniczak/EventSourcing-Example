<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;


class QuantityOfTheProductHasBeenChangedTest extends SerializationTestCase
{
    protected function getJsonFileName(): string
    {
        return 'QuantityOfTheProductHasBeenChanged.json';
    }

    protected function getEvent(): Event
    {
        return new QuantityOfTheProductHasBeenChanged(
            $this->generateEventId('6a5e9cd4-225a-4aa4-986b-8d6e9248c1cf'),
            $this->generateDateTime('2017-01-26T08:51:10+0100'),
            new BasketId('b70fce84-2a90-46e4-9867-e6b2574aadd9'),
            new ProductId('ed6fe093-825c-444b-b888-75350037fd93'),
            1.1
        );
    }


}
