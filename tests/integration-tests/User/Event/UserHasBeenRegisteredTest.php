<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class UserHasBeenRegisteredTest extends SerializationTestCase
{

    public function testOutputJson()
    {
        $this->assertIdentical($this->loadJsonFromFile('UserHasBeenRegistered.json'), $this->getJson());
    }

    protected function getJson(): string
    {
        $event = new UserHasBeenRegistered(
            $this->generateEventId('0f51d839-bfdd-47ee-bba6-913fedfbfbd7'),
            $this->generateDateTime('2017-01-22T10:33:52+0100'),
            'user@user.com',
            '$2y$10$wKCkjofrs4YVNpV7OYz/aOx4mnUxJNUJx4CNsnJuKimKssMMt4yYy'
        );

        return $this->serializer->serialize($event);
    }


}
