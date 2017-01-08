<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User;


use BartoszBartniczak\ArrayObject\ArrayObject;
use BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\User\Event\ActivationTokenHasBeenGenerated;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UnsuccessfulAttemptOfLoggingIn;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserAccountHasBeenActivated;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenLoggedIn;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenLoggedOut;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenRegistered;

class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::getEmail
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::getPasswordHash
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::isActive
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::getLoginDates
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::getUnsuccessfulAttemptsOfActivatingUserAccount
     */
    public function testConstructor()
    {
        $user = new User('user@user.com', 'password');
        $this->assertInstanceOf(EventAggregate::class, $user);

        $this->assertEquals('user@user.com', $user->getEmail());
        $this->assertEquals('password', $user->getPasswordHash());

        $this->assertFalse($user->isActive());
        $this->assertInstanceOf(ArrayObject::class, $user->getLoginDates());
        $this->assertEquals(0, $user->getLoginDates()->count());
        $this->assertEquals(0, $user->getUnsuccessfulAttemptsOfActivatingUserAccount());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::handleUserHasBeenRegistered
     */
    public function testHandleUserHasBeenRegistered()
    {
        $userHasBeenRegisteredEvent = $this->getMockBuilder(UserHasBeenRegistered::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getUserEmail',
                'getPasswordHash',
            ])
            ->getMock();

        $userHasBeenRegisteredEvent->method('getUserEmail')
            ->willReturn('user@user.com');

        $userHasBeenRegisteredEvent->method('getPasswordHash')
            ->willReturn('password');
        /* @var $userHasBeenRegisteredEvent UserHasBeenRegistered */

        $user = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                'empty', 'empty', 'empty'
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleUserHasBeenRegistered');

        /* @var $user \BartoszBartniczak\EventSourcing\Shop\User\User */
        $user->apply($userHasBeenRegisteredEvent);

        $this->assertEquals('user@user.com', $user->getEmail());
        $this->assertEquals('password', $user->getPasswordHash());
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $this->assertEquals(1, $user->getUncommittedEvents()->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::handleActivationTokenHasBeenGenerated
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::getActivationToken
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::changeActivationToken
     */
    public function testHandleActivationTokenHasBeenGenerated()
    {

        $activationTokenHasBeenGenerated = $this->getMockBuilder(ActivationTokenHasBeenGenerated::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getActivationToken'
            ])
            ->getMock();
        $activationTokenHasBeenGenerated->method('getActivationToken')
            ->willReturn('newToken');
        /* @var $activationTokenHasBeenGenerated ActivationTokenHasBeenGenerated * */

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleActivationTokenHasBeenGenerated');
        /* @var $user \BartoszBartniczak\EventSourcing\Shop\User\User */

        $user->apply($activationTokenHasBeenGenerated);
        $this->assertEquals('newToken', $user->getActivationToken());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::handleUnsuccessfulAttemptOfActivatingUserAccount
     */
    public function testHandleUnsuccessfulAttemptOfActivatingUserAccount()
    {
        $unsuccessfulAttemptsOfActivatingUserAccount = $this->getMockBuilder(UnsuccessfulAttemptOfActivatingUserAccount::class)
            ->disableOriginalConstructor()
            ->getMock();

        /* @var $unsuccessfulAttemptsOfActivatingUserAccount UnsuccessfulAttemptOfActivatingUserAccount */


        $user = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleUnsuccessfulAttemptOfActivatingUserAccount');
        /* @var $user \BartoszBartniczak\EventSourcing\Shop\User\User */

        $this->assertEquals(0, $user->getUnsuccessfulAttemptsOfActivatingUserAccount());
        $user->apply($unsuccessfulAttemptsOfActivatingUserAccount);
        $this->assertEquals(1, $user->getUnsuccessfulAttemptsOfActivatingUserAccount());
        $user->apply($unsuccessfulAttemptsOfActivatingUserAccount);
        $this->assertEquals(2, $user->getUnsuccessfulAttemptsOfActivatingUserAccount());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::handleUserAccountHasBeenActivated
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::activate
     */
    public function testHandleUserAccountHasBeenActivated()
    {

        $userAccountHasBeenActivated = $this->getMockBuilder(UserAccountHasBeenActivated::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $userAccountHasBeenActivated UserAccountHasBeenActivated */

        $user = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleUserAccountHasBeenActivated');
        /* @var $user \BartoszBartniczak\EventSourcing\Shop\User\User */

        $user->apply($userAccountHasBeenActivated);
        $this->assertTrue($user->isActive());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::handleUserHasBeenLoggedIn
     */
    public function testHandleUserHasBeenLoggedIn()
    {
        $dateTime1 = new \DateTime();
        $dateTime2 = new \DateTime();

        $userHasBeenLoggedIn = $this->getMockBuilder(UserHasBeenLoggedIn::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getDateTime'
            ])
            ->getMock();
        $userHasBeenLoggedIn->expects($this->atLeast(2))
            ->method('getDateTime')
            ->willReturnOnConsecutiveCalls(
                $dateTime1,
                $dateTime2
            );
        /* @var $userHasBeenLoggedIn UserHasBeenLoggedIn */

        $user = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleUserHasBeenLoggedIn');
        /* @var $user \BartoszBartniczak\EventSourcing\Shop\User\User */

        $user->apply($userHasBeenLoggedIn);
        $user->apply($userHasBeenLoggedIn);
        $this->assertEquals(2, $user->getLoginDates()->count());
        $this->assertSame($dateTime1, $user->getLoginDates()->offsetGet(0));
        $this->assertSame($dateTime2, $user->getLoginDates()->offsetGet(1));
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::handleUserHasBeenLoggedOut
     */
    public function testHandleUserHasBeenLoggedOut()
    {
        $userHasBeenLoggedOut = $this->getMockBuilder(UserHasBeenLoggedOut::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $userHasBeenLoggedOut UserHasBeenLoggedIn */

        $user = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleUserHasBeenLoggedOut');
        /* @var $user \BartoszBartniczak\EventSourcing\Shop\User\User */

        $user->apply($userHasBeenLoggedOut);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::handleUnsuccessfulAttemptOfLoggingIn
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\User::getUnsuccessfulAttemptsOfLoggingIn
     */
    public function testHandleUnsuccessfulAttemptOfLoggingIn()
    {

        $unsuccessfulAttemptOfLoggingIn = $this->getMockBuilder(UnsuccessfulAttemptOfLoggingIn::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $unsuccessfulAttemptOfLoggingIn UnsuccessfulAttemptOfLoggingIn */

        $user = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                'user@email', 'password', 'salt'
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleUnsuccessfulAttemptOfLoggingIn');
        /* @var $user User */

        $user->apply($unsuccessfulAttemptOfLoggingIn);

        $this->assertEquals(1, $user->getUncommittedEvents()->count());
        $this->assertInstanceOf(UnsuccessfulAttemptOfLoggingIn::class, $user->getUncommittedEvents()->shift());
        $this->assertEquals(1, $user->getUnsuccessfulAttemptsOfLoggingIn());

    }
}
