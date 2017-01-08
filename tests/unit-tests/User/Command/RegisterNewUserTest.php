<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command;


use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Email\Sender\Service;
use BartoszBartniczak\EventSourcing\Shop\Generator\ActivationTokenGenerator;
use BartoszBartniczak\EventSourcing\Shop\Password\HashGenerator;
use BartoszBartniczak\EventSourcing\Shop\Password\SaltGenerator;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator;

class RegisterNewUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser::getUserEmail
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser::getUserPassword
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser::getEmailSenderService
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser::getActivationTokenGenerator
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser::getUuidGenerator
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser::getHashGenerator
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser::getEmail
     */
    public function testGetters()
    {

        $emailSenderService = $this->getMockBuilder(Service::class)
            ->getMock();
        /* @var $emailSenderService Service */

        $activationTokenGenerator = $this->getMockBuilder(ActivationTokenGenerator::class)
            ->getMock();
        /* @var $activationTokenGenerator ActivationTokenGenerator */

        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $uuidGenerator Generator */

        $saltGenerator = $this->getMockBuilder(SaltGenerator::class)
            ->getMock();
        /* @var $saltGenerator SaltGenerator */

        $hashGenerator = $this->getMockBuilder(HashGenerator::class)
            ->getMock();
        /* @var $hashGenerator HashGenerator */

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */

        $command = new RegisterNewUser(
            'user@email.com', 'password', $emailSenderService, $activationTokenGenerator, $uuidGenerator, $hashGenerator, $email
        );

        $this->assertSame('user@email.com', $command->getUserEmail());
        $this->assertSame('password', $command->getUserPassword());
        $this->assertSame($emailSenderService, $command->getEmailSenderService());
        $this->assertSame($activationTokenGenerator, $command->getActivationTokenGenerator());
        $this->assertSame($uuidGenerator, $command->getUuidGenerator());
        $this->assertSame($hashGenerator, $command->getHashGenerator());
        $this->assertSame($email, $command->getEmail());

    }

}
