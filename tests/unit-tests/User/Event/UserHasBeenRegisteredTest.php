<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Shop\EventTestCase;

class UserHasBeenRegisteredTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenRegistered::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenRegistered::getPasswordHash
     */
    public function testGetters()
    {
        $event = new UserHasBeenRegistered(
            $this->generateEventId(),
            $this->generateDateTime(),
            'user@email.com',
            'password',
            'salt'
        );

        $this->assertInstanceOf(Event::class, $event);
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertEquals('user@email.com', $event->getUserEmail());
        $this->assertEquals('password', $event->getPasswordHash());
    }

}
