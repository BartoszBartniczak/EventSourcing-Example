<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Shop\EventTestCase;

class UserAccountHasBeenActivatedTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Event\UserAccountHasBeenActivated::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Event\UserAccountHasBeenActivated::getActivationToken
     */
    public function testGetters()
    {

        $event = new UserAccountHasBeenActivated(
            $this->generateEventId(),
            $this->generateDateTime(),
            'user@user.com',
            'activationToken'
        );

        $this->assertInstanceOf(Event::class, $event);
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertSame('user@user.com', $event->getUserEmail());
        $this->assertSame('activationToken', $event->getActivationToken());
    }


}
