<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Event;


use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\DeSerializationTestCase;
use BartoszBartniczak\EventSourcing\Shop\Email\Factory\Factory;

class EmailHasNotBeenSentTest extends DeSerializationTestCase
{

    protected function getJsonFileName(): string
    {
        return 'EmailHasNotBeenSent.json';
    }

    protected function getEvent(): Event
    {
        $factory = new Factory($this->uuidGenerator);
        $email = $factory->createNew('03b4a547-b951-4bc8-87fb-108646e753e7');

        return new EmailHasNotBeenSent(
            $this->generateEventId('e41d4459-05a3-4c02-a359-0045e42ca1ba'),
            $this->generateDateTime('2017-01-23T10:33:21+0100'),
            $email,
            "You are using NullEmailSenderService!"
        );
    }

}
