<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory;
use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class BasketHasBeenCreatedTest extends SerializationTestCase
{

    protected function getJsonFileName(): string
    {
        return 'BasketHasBeenCreated.json';
    }

    protected function getEvent(): Event
    {
        $factory = new Factory($this->uuidGenerator);
        $basket = $factory->createNew('user@user.com', '073ead35-8fb5-41cb-9c68-9ac0defe4970');

        return $event = new BasketHasBeenCreated(
            $this->generateEventId('c3e9f89e-cb7b-407d-8d6c-19c5be66f081'),
            $this->generateDateTime('2017-01-23T12:34:36+0100'),
            $basket->getId(),
            $basket->getOwnerEmail()
        );
    }


}
