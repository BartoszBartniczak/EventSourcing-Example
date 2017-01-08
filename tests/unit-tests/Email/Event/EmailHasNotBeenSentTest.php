<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Event;


use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class EmailHasNotBeenSentTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Event\EmailHasNotBeenSent::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Event\EmailHasNotBeenSent::getErrorMessage()
     */
    public function testGetters()
    {

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */

        $emailHasNotBeenSent = new EmailHasNotBeenSent(
            $this->generateEventId(),
            $this->generateDateTime(),
            $email,
            'Message'
        );

        $this->assertInstanceOf(Event::class, $emailHasNotBeenSent);
        $this->assertSameEventIdAsGenerated($emailHasNotBeenSent);
        $this->assertSameDateTimeAsGenerated($emailHasNotBeenSent);
        $this->assertSame($email, $emailHasNotBeenSent->getEmail());
        $this->assertSame('Message', $emailHasNotBeenSent->getErrorMessage());

    }

}
