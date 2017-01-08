<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command\Handler;

use BartoszBartniczak\EventSourcing\Shop\User\Command\ActivateUser as ActivateUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserAccountHasBeenActivated;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\UserRepository;
use BartoszBartniczak\EventSourcing\Shop\User\User;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator;

class ActivateUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\ActivateUser::handle
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\ActivateUser::tokenValidation
     */
    public function testHandleValidActivationToken()
    {
        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'getActivationToken'
            ])
            ->getMock();
        $userMock->method('getActivationToken')
            ->willReturn('activationToken');
        /* @var $userMock User */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setMethods([
                'findUserByEmail'
            ])
            ->getMock();

        $userRepository->expects($this->once())
            ->method('findUserByEmail')
            ->with('email@user.com')
            ->willReturn($userMock);
        /* @var $userRepository UserRepository */

        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $uuidGenerator Generator */

        $command = new ActivateUserCommand(
            'email@user.com',
            'activationToken',
            $userRepository
        );

        $activateUserHandler = new ActivateUser($uuidGenerator);
        $user = $activateUserHandler->handle($command);
        $this->assertTrue($user->isActive());
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $userAccountHasBeenActivatedEvent = $user->getUncommittedEvents()->shift();
        $this->assertInstanceOf(UserAccountHasBeenActivated::class, $userAccountHasBeenActivatedEvent);
        /* @var $userAccountHasBeenActivatedEvent UserAccountHasBeenActivated */
        $this->assertSame('email@user.com', $userAccountHasBeenActivatedEvent->getUserEmail());
        $this->assertSame('activationToken', $userAccountHasBeenActivatedEvent->getActivationToken());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\ActivateUser::handle
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\ActivateUser::tokenValidation
     */
    public function testHandleUserHasBeenAlreadyActivated()
    {
        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $uuidGenerator Generator */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setMethods([
                'findUserByEmail'
            ])
            ->getMock();

        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'getActivationToken',
                'isActive'
            ])
            ->getMock();
        $userMock->method('getActivationToken')
            ->willReturn('activationToken');
        $userMock->method('isActive')
            ->willReturn(true);
        /* @var $userMock User */

        $userRepository->expects($this->once())
            ->method('findUserByEmail')
            ->with('email@user.com')
            ->willReturn($userMock);
        /* @var $userRepository UserRepository */

        $command = new ActivateUserCommand(
            'email@user.com',
            'activationToken',
            $userRepository
        );

        $activateUserHandler = new ActivateUser($uuidGenerator);
        $user = $activateUserHandler->handle($command);
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $unsuccessfulAttemptOfActivatingUserAccount = $user->getUncommittedEvents()->shift();
        /* @var $unsuccessfulAttemptOfActivatingUserAccount UnsuccessfulAttemptOfActivatingUserAccount */
        $this->assertInstanceOf(UnsuccessfulAttemptOfActivatingUserAccount::class, $unsuccessfulAttemptOfActivatingUserAccount);
        $this->assertSame('email@user.com', $unsuccessfulAttemptOfActivatingUserAccount->getUserEmail());
        $this->assertSame('activationToken', $unsuccessfulAttemptOfActivatingUserAccount->getActivationToken());
        $this->assertSame('User has been already activated.', $unsuccessfulAttemptOfActivatingUserAccount->getMessage());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\ActivateUser::handle
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\ActivateUser::tokenValidation
     */
    public function testHandleInvalidActivationToken()
    {

        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $uuidGenerator Generator */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setMethods([
                'findUserByEmail'
            ])
            ->getMock();

        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'getActivationToken',
            ])
            ->getMock();
        $userMock->method('getActivationToken')
            ->willReturn('activationToken');
        /* @var $userMock User */

        $userRepository->expects($this->once())
            ->method('findUserByEmail')
            ->with('email@user.com')
            ->willReturn($userMock);
        /* @var $userRepository UserRepository */

        $command = new ActivateUserCommand(
            'email@user.com',
            'wrongActivationToken',
            $userRepository
        );


        $activateUserHandler = new ActivateUser($uuidGenerator);
        $user = $activateUserHandler->handle($command);
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $unsuccessfulAttemptOfActivatingUserAccount = $user->getUncommittedEvents()->shift();
        /* @var $unsuccessfulAttemptOfActivatingUserAccount UnsuccessfulAttemptOfActivatingUserAccount */
        $this->assertInstanceOf(UnsuccessfulAttemptOfActivatingUserAccount::class, $unsuccessfulAttemptOfActivatingUserAccount);
        $this->assertSame('email@user.com', $unsuccessfulAttemptOfActivatingUserAccount->getUserEmail());
        $this->assertSame('wrongActivationToken', $unsuccessfulAttemptOfActivatingUserAccount->getActivationToken());
        $this->assertSame('Invalid activation token.', $unsuccessfulAttemptOfActivatingUserAccount->getMessage());
    }

}
