<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command\Handler;

use BartoszBartniczak\EventSourcing\Shop\User\Command\LogOutUser as LogOutUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenLoggedOut;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\UserRepository;
use BartoszBartniczak\EventSourcing\Shop\User\User;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator;

class LogOutUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\LogOutUser::handle
     */
    public function testHandle()
    {

        $userEmail = 'user@email.com';

        $generator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $generator Generator */

        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs(
                ['', '', '']
            )
            ->setMethods(null)
            ->getMock();
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

        $logOutUserCommand = new LogOutUserCommand($userEmail, $userRepository);

        $logOutUserHandler = new LogOutUser($generator);
        $user = $logOutUserHandler->handle($logOutUserCommand);
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $this->assertEquals(1, $user->getUncommittedEvents()->count());
        $userHasBeenLogOut = $user->getUncommittedEvents()->shift();
        $this->assertInstanceOf(UserHasBeenLoggedOut::class, $userHasBeenLogOut);
        /* @var $userHasBeenLogOut UserHasBeenLoggedOut */
        $this->assertSame('user@email.com', $userHasBeenLogOut->getUserEmail());
    }

}
