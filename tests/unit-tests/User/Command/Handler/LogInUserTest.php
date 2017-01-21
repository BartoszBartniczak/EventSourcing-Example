<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command\Handler;


use BartoszBartniczak\CQRS\Command\Handler\CannotHandleTheCommandException;
use BartoszBartniczak\EventSourcing\Shop\Password\HashGenerator;
use BartoszBartniczak\EventSourcing\Shop\User\Command\LogInUser as LogInUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Event\AttemptOfLoggingInToInactiveAccount;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\CannotFindUserException;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\UserRepository;
use BartoszBartniczak\EventSourcing\Shop\User\User;
use BartoszBartniczak\EventSourcing\UUID\Generator;

class LogInUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\LogInUser::handle
     */
    public function testHandleValidPassword()
    {

        $hashGenerator = $this->getMockBuilder(HashGenerator::class)
            ->setMethods([
                'hash',
                'verifyUserPassword'
            ])
            ->getMock();
        $hashGenerator->method('hash')
            ->willReturn('passwordHash');
        $hashGenerator->method('verifyUserPassword')
            ->willReturn(true);
        /* @var $hashGenerator HashGenerator */

        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'isActive'
            ])
            ->getMock();
        $userMock->method('isActive')
            ->willReturn(true);
        /* @var $userMock User */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setMethods([
                'findUserByEmail'
            ])
            ->disableOriginalConstructor()
            ->getMock();
        $userRepository->method('findUserByEmail')
            ->willReturn($userMock);
        /* @var $userRepository UserRepository */

        $generator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $generator Generator */

        $command = new LogInUserCommand(
            'email@user.com',
            'password',
            $hashGenerator,
            $userRepository
        );

        $logInUserHandler = new LogInUser($generator);
        $user = $logInUserHandler->handle($command);

        $this->assertEquals(1, $user->getLoginDates()->count());
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $userHasBeenLoggedIn = $user->getUncommittedEvents()->shift();
        /* @var $userHasBeenLoggedIn \Shop\User\Event\UserHasBeenLoggedIn */
        $this->assertSame($userHasBeenLoggedIn->getDateTime(), $user->getLoginDates()->shift());
        $this->assertSame('email@user.com', $userHasBeenLoggedIn->getUserEmail());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\LogInUser::handle
     */
    public function testHandleInvalidPassword()
    {

        $hashGenerator = $this->getMockBuilder(HashGenerator::class)
            ->setMethods([
                'hash',
                'verifyUserPassword'
            ])
            ->getMock();
        $hashGenerator->method('hash')
            ->willReturn('passwordHash');
        $hashGenerator->method('verifyUserPassword')
            ->willReturn(false);
        /* @var $hashGenerator HashGenerator */

        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'isActive'
            ])
            ->getMock();
        $userMock->method('isActive')
            ->willReturn(true);
        /* @var $userMock User */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setMethods([
                'findUserByEmail'
            ])
            ->disableOriginalConstructor()
            ->getMock();
        $userRepository->method('findUserByEmail')
            ->willReturn($userMock);
        /* @var $userRepository UserRepository */

        $generator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $generator Generator */

        $command = new LogInUserCommand(
            'email@user.com',
            'password',
            $hashGenerator,
            $userRepository
        );

        $logInUserHandler = new LogInUser($generator);
        $user = $logInUserHandler->handle($command);

        $this->assertEquals(0, $user->getLoginDates()->count());
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $unsuccessfulAttemptOfLoggingIn = $user->getUncommittedEvents()->shift();
        /* @var $unsuccessfulAttemptOfLoggingIn \Shop\User\Event\UnsuccessfulAttemptOfLoggingIn */
        $this->assertSame('email@user.com', $unsuccessfulAttemptOfLoggingIn->getUserEmail());
        $this->assertEquals(1, $user->getUnsuccessfulAttemptsOfLoggingIn());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\LogInUser::handle
     */
    public function testHandleInactiveAccount()
    {
        $hashGenerator = new HashGenerator();

        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs(['email@test.com',
                $hashGenerator->hash('password')
            ])
            ->setMethods([
                'isActive',
            ])
            ->getMock();
        $userMock->method('isActive')
            ->willReturn(false);
        /* @var $userMock User */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'findUserByEmail'
            ])
            ->getMock();
        $userRepository->method('findUserByEmail')
            ->willReturn($userMock);
        /* @var $userRepository UserRepository */

        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $uuidGenerator Generator */

        $logInUserCommand = new LogInUserCommand('email@test.com', 'password', $hashGenerator, $userRepository);
        $logInUser = new LogInUser($uuidGenerator);
        $user = $logInUser->handle($logInUserCommand);

        $this->assertInstanceOf(AttemptOfLoggingInToInactiveAccount::class, $user->getUncommittedEvents()->last());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\LogInUser::handle
     */
    public function testHandleThrowsCannotHandleExceptionIfUserDoesNotExist()
    {
        $this->expectException(CannotHandleTheCommandException::class);
        $this->expectExceptionMessage('User does not exist.');

        $hashGenerator = new HashGenerator();
        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'findUserByEmail'
            ])
            ->getMock();
        $userRepository->method('findUserByEmail')
            ->willThrowException(new CannotFindUserException());
        /* @var $userRepository UserRepository */

        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $uuidGenerator Generator */

        $logInUserCommand = new LogInUserCommand('email@test.com', 'password', $hashGenerator, $userRepository);
        $logInUser = new LogInUser($uuidGenerator);
        $logInUser->handle($logInUserCommand);
    }


}
