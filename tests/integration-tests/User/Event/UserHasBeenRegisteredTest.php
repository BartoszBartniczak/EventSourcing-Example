<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class UserHasBeenRegisteredTest extends SerializationTestCase
{

    protected function getJsonFileName(): string
    {
        return 'UserHasBeenRegistered.json';
    }

    protected function getEvent(): Event
    {
        return new UserHasBeenRegistered(
            $this->generateEventId('0f51d839-bfdd-47ee-bba6-913fedfbfbd7'),
            $this->generateDateTime('2017-01-22T10:33:52+0100'),
            'user@user.com',
            '$2y$10$wKCkjofrs4YVNpV7OYz/aOx4mnUxJNUJx4CNsnJuKimKssMMt4yYy'
        );
    }


}
