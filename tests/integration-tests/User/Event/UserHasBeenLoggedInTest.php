<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\DeSerializationTestCase;

class UserHasBeenLoggedInTest extends DeSerializationTestCase
{
    protected function getJsonFileName(): string
    {
        return 'UserHasBeenLoggedIn.json';
    }

    protected function getEvent(): Event
    {
        return new UserHasBeenLoggedIn(
            $this->generateEventId('ed6af706-193a-4c3b-a149-2ab075ef30c7'),
            $this->generateDateTime('2017-01-23T12:34:36+0100'),
            'user@user.com'
        );
    }

}
