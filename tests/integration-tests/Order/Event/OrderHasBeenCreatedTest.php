<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Event;


use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\DeSerializationTestCase;
use BartoszBartniczak\EventSourcing\Shop\Order\Id as OrderId;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\Position;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\PositionArray as OrderPositions;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\ProductIdStrategy;
use BartoszBartniczak\EventSourcing\Shop\Product\Factory\Factory as ProductFactory;

class OrderHasBeenCreatedTest extends DeSerializationTestCase
{
    protected function getJsonFileName(): string
    {
        return 'OrderHasBeenCreated.json';
    }

    protected function getEvent(): Event
    {
        $productFactory = new ProductFactory($this->uuidGenerator);

        return new OrderHasBeenCreated(
            $this->generateEventId('eb7094ff-c48d-4b5b-9d6d-0acda5a74b09'),
            $this->generateDateTime("2017-01-26T12:16:01+0100"),
            new OrderId("001c8a7c-94b1-4625-aab5-086b30ee7e8b"),
            new BasketId("bcb49e53-35ee-4863-adfc-b4ca10e80d3a"),
            new OrderPositions(
                new ProductIdStrategy(),
                [
                    '518ad57c-9e93-4410-8792-5fd37b74bc5e' => new Position(
                        $productFactory->createNew('Milk', '518ad57c-9e93-4410-8792-5fd37b74bc5e'),
                        2.0
                    ),
                    '5f88bdad-0fc7-447b-b1dc-d6379c9cab2e' => new Position(
                        $productFactory->createNew('Butter', '5f88bdad-0fc7-447b-b1dc-d6379c9cab2e'),
                        1.0
                    )
                ]
            )
        );
    }


}
