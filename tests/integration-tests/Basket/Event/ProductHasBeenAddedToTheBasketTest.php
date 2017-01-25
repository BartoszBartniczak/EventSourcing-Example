<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory as BasketFactory;
use BartoszBartniczak\EventSourcing\Shop\Product\Factory\Factory as ProductFactory;
use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class ProductHasBeenAddedToTheBasketTest extends SerializationTestCase
{
    public function testOutputJson()
    {
        $this->assertIdentical($this->loadJsonFromFile('ProductHasBeenAddedToTheBasket.json'), $this->getJson());
    }

    protected function getJson(): string
    {
        $basketFactory = new BasketFactory($this->uuidGenerator);
        $productFactory = new ProductFactory($this->uuidGenerator);

        $basket = $basketFactory->createNew('user@user.com', '073ead35-8fb5-41cb-9c68-9ac0defe4970');
        $product = $productFactory->createNew("Test", '46bfb7df-d5c3-420f-be49-713caa53062f');

        $event = new ProductHasBeenAddedToTheBasket(
            $this->generateEventId('70d448e1-ee2d-492c-a89d-b65f9078e221'),
            $this->generateDateTime('2017-01-23T12:34:36+0100'),
            $basket->getId(),
            $product->getId(),
            2.5
        );

        return $this->serializer->serialize($event);
    }


}
