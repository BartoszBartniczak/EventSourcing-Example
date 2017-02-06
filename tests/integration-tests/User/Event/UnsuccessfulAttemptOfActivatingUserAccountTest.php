<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class UnsuccessfulAttemptOfActivatingUserAccountTest extends SerializationTestCase
{


    protected function getJsonFileName(): string
    {
        return 'UnsuccessfulAttemptOfActivatingUserAccount.json';
    }

    protected function getEvent(): Event
    {
        return new UnsuccessfulAttemptOfActivatingUserAccount(
            $this->generateEventId('be0d4de8-f4cf-4943-b009-01d9de8e0eed'),
            $this->generateDateTime('2017-01-23T12:34:36+0100'),
            "user@user.com",
            "xxx",
            "Invalid activation token."
        );
    }


}
