<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Event;


use BartoszBartniczak\EventSourcing\Shop\Email\Factory\Factory;
use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class EmailHasBeenSentTest extends SerializationTestCase
{
    public function testOutputJson()
    {
        $this->assertIdentical($this->loadJsonFromFile('EmailHasBeenSent.json'), $this->getJson());
    }

    protected function getJson(): string
    {
        $factory = new Factory($this->uuidGenerator);
        $email = $factory->createNew('8e1d6661-3fa2-436d-89bb-3164adf7f163');

        $event = new EmailHasBeenSent(
            $this->generateEventId('94c027b5-b3e8-4c55-a289-9c0660494335'),
            $this->generateDateTime('2017-01-23T12:34:36+0100'),
            $email
        );

        return $this->serializer->serialize($event);
    }


}
