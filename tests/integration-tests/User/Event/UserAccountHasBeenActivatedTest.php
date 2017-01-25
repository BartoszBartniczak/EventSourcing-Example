<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class UserAccountHasBeenActivatedTest extends SerializationTestCase
{

    public function testOutputJson()
    {
        $this->assertIdentical($this->loadJsonFromFile('UserAccountHasBeenActivated.json'), $this->getJson());
    }

    protected function getJson(): string
    {
        $event = new UserAccountHasBeenActivated(
            $this->generateEventId('e725c8af-ef0d-479d-95bf-ab6238fc5d7f'),
            $this->generateDateTime('2017-01-23T12:34:36+0100'),
            'user@user.com',
            '5885ea4c4bd4d'
        );

        return $this->serializer->serialize($event);
    }


}
