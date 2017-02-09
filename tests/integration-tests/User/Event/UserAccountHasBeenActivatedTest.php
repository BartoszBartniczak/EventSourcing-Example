<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\DeSerializationTestCase;

class UserAccountHasBeenActivatedTest extends DeSerializationTestCase
{

    protected function getJsonFileName(): string
    {
        return 'UserAccountHasBeenActivated.json';
    }

    protected function getEvent(): Event
    {
        return new UserAccountHasBeenActivated(
            $this->generateEventId('e725c8af-ef0d-479d-95bf-ab6238fc5d7f'),
            $this->generateDateTime('2017-01-23T12:34:36+0100'),
            'user@user.com',
            '5885ea4c4bd4d'
        );
    }


}
