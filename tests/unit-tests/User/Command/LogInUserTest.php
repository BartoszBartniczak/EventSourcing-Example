<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command;


use BartoszBartniczak\EventSourcing\Shop\Password\HashGenerator;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\UserRepository;

class LogInUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\LogInUser::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\LogInUser::getPassword
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\LogInUser::getUserEmail
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\LogInUser::getHashGenerator
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\LogInUser::getUserRepository
     */
    public function testGetters()
    {

        $hashGenerator = $this->getMockBuilder(HashGenerator::class)
            ->getMock();
        /* @var $hashGenerator HashGenerator */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $userRepository UserRepository */

        $command = new LogInUser(
            'email@user.com',
            'password',
            $hashGenerator,
            $userRepository
        );
        $this->assertSame('email@user.com', $command->getUserEmail());
        $this->assertSame('password', $command->getPassword());
        $this->assertSame($hashGenerator, $command->getHashGenerator());
        $this->assertSame($userRepository, $command->getUserRepository());
    }

}
