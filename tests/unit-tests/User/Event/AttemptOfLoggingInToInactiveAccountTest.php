<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;

use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class AttemptOfLoggingInToInactiveAccountTest extends EventTestCase
{

    public function testConstructor()
    {
        $attemptOfLoggingInToInactiveAccount = new AttemptOfLoggingInToInactiveAccount(
            $this->generateEventId(),
            $this->generateDateTime(),
            'test@email.com'
        );
        $this->assertInstanceOf(UnsuccessfulAttemptOfLoggingIn::class, $attemptOfLoggingInToInactiveAccount);
        $this->assertSameEventIdAsGenerated($attemptOfLoggingInToInactiveAccount);
        $this->assertSameDateTimeAsGenerated($attemptOfLoggingInToInactiveAccount);
        $this->assertSame('test@email.com', $attemptOfLoggingInToInactiveAccount->getUserEmail());
    }

}
