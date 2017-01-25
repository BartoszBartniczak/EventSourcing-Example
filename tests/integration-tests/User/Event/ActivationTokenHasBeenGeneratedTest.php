<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Shop\SerializationTestCase;

class ActivationTokenHasBeenGeneratedTest extends SerializationTestCase
{
    public function testOutputJson()
    {
        $this->assertIdentical($this->loadJsonFromFile('ActivationTokenHasBeenGenerated.json'), $this->getJson());
    }

    protected function getJson(): string
    {
        $event = new ActivationTokenHasBeenGenerated(
            $this->generateEventId('40a0ab50-5029-4574-9790-8e5132422124'),
            $this->generateDateTime('2017-01-23T10:33:21+0100'),
            'user@user.com',
            '5885cde1a1471'
        );
        return $this->serializer->serialize($event);
    }


}
