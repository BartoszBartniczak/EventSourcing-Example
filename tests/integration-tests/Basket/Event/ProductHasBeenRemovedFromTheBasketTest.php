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


class ProductHasBeenRemovedFromTheBasketTest extends SerializationTestCase
{

    protected function getJsonFileName(): string
    {
        return 'ProductHasBeenRemovedFromTheBasket.json';
    }

    protected function getEvent(): Event
    {
        return new ProductHasBeenRemovedFromTheBasket(
            $this->generateEventId('cc65c388-5ec4-49c4-82fd-e270c03d019f'),
            $this->generateDateTime('2017-01-26T08:51:10+0100'),
            new BasketId('b70fce84-2a90-46e4-9867-e6b2574aadd9'),
            new ProductId('1e5ae4d6-8ecb-4a7f-8699-5e6a9216e32b')
        );
    }


}
