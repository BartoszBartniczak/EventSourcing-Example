<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Event;


use BartoszBartniczak\EventSourcing\Test\EventTestCase;


class AttemptOfActivatingAlreadyAcivatedAccountTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Event\AttemptOfActivatingAlreadyActivatedAccount::__construct
     */
    public function testConstructor()
    {

        $attemptOfActivatingAlreadyActivatedAccount = new AttemptOfActivatingAlreadyActivatedAccount(
            $this->generateEventId(),
            $this->generateDateTime(),
            'email@test.pl',
            'token',
            'Error message.'
        );
        $this->assertInstanceOf(UnsuccessfulAttemptOfActivatingUserAccount::class, $attemptOfActivatingAlreadyActivatedAccount);
        $this->assertSameEventIdAsGenerated($attemptOfActivatingAlreadyActivatedAccount);
        $this->assertSameDateTimeAsGenerated($attemptOfActivatingAlreadyActivatedAccount);
        $this->assertSame('email@test.pl', $attemptOfActivatingAlreadyActivatedAccount->getUserEmail());
        $this->assertSame('token', $attemptOfActivatingAlreadyActivatedAccount->getActivationToken());
        $this->assertSame('Error message.', $attemptOfActivatingAlreadyActivatedAccount->getMessage());
    }

}
