<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Event;


use BartoszBartniczak\EventSourcing\Shop\Email\Factory\Factory;
use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class EmailHasNotBeenSentTest extends SerializationTestCase
{
    public function testOutputJson()
    {
        $this->assertIdentical($this->loadJsonFromFile('EmailHasNotBeenSent.json'), $this->getJson());
    }

    protected function getJson(): string
    {
        $factory = new Factory($this->uuidGenerator);
        $email = $factory->createNew('03b4a547-b951-4bc8-87fb-108646e753e7');

        $event = new EmailHasNotBeenSent(
            $this->generateEventId('e41d4459-05a3-4c02-a359-0045e42ca1ba'),
            $this->generateDateTime('2017-01-23T10:33:21+0100'),
            $email,
            "You are using NullEmailSenderService!"
        );
        return $this->serializer->serialize($event);
    }


}
