<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class UserHasBeenLoggedOutTest extends SerializationTestCase
{
    protected function getJsonFileName(): string
    {
        return 'UserHasBeenLoggedOut.json';
    }

    protected function getEvent(): Event
    {
        return new UserHasBeenLoggedOut(
            $this->generateEventId('99f9ad90-38f2-4392-ad50-a19c1365cb9a'),
            $this->generateDateTime('2017-01-26T08:51:10+0100'),
            'user@user.com'
        );
    }


}
