<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class UserHasBeenLoggedInTest extends SerializationTestCase
{
    public function testOutputJson()
    {
        $this->assertIdentical($this->loadJsonFromFile('UserHasBeenLoggedIn.json'), $this->getJson());
    }

    protected function getJson(): string
    {
        $event = new UserHasBeenLoggedIn(
            $this->generateEventId('ed6af706-193a-4c3b-a149-2ab075ef30c7'),
            $this->generateDateTime('2017-01-23T12:34:36+0100'),
            'user@user.com'
        );

        return $this->serializer->serialize($event);
    }


}
