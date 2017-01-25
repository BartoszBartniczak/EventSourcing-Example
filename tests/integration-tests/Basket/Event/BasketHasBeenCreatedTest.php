<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory;
use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class BasketHasBeenCreatedTest extends SerializationTestCase
{
    public function testOutputJson()
    {
        $this->assertIdentical($this->loadJsonFromFile('BasketHasBeenCreated.json'), $this->getJson());
    }

    protected function getJson(): string
    {
        $factory = new Factory($this->uuidGenerator);
        $basket = $factory->createNew('user@user.com', '073ead35-8fb5-41cb-9c68-9ac0defe4970');

        $event = new BasketHasBeenCreated(
            $this->generateEventId('c3e9f89e-cb7b-407d-8d6c-19c5be66f081'),
            $this->generateDateTime('2017-01-23T12:34:36+0100'),
            $basket->getId(),
            $basket->getOwnerEmail()
        );

        return $this->serializer->serialize($event);
    }


}
