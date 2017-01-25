<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class AttemptOfActivatingAlreadyActivatedAccountTest extends SerializationTestCase
{
    public function testOutputJson()
    {
        $this->assertIdentical($this->loadJsonFromFile('AttemptOfActivatingAlreadyActivatedAccount.json'), $this->getJson());
    }

    protected function getJson(): string
    {
        $event = new AttemptOfActivatingAlreadyActivatedAccount(
            $this->generateEventId('eccacc01-db93-4738-9b82-a3737843f617'),
            $this->generateDateTime('2017-01-23T12:34:36+0100'),
            "user@user.com",
            "5885ea4c4bd4d",
            "User has been already activated."
        );

        return $this->serializer->serialize($event);
    }


}
