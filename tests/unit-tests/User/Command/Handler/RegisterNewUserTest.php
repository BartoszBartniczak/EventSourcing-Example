<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command\Handler;


use BartoszBartniczak\EventSourcing\Shop\Email\Command\SendEmail;
use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Email\Sender\Service;
use BartoszBartniczak\EventSourcing\Shop\Generator\ActivationTokenGenerator;
use BartoszBartniczak\EventSourcing\Shop\Password\HashGenerator;
use BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser as RegisterNewUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Event\ActivationTokenHasBeenGenerated;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenRegistered;
use BartoszBartniczak\EventSourcing\Shop\User\User;
use BartoszBartniczak\EventSourcing\UUID\Generator;

class RegisterNewUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\RegisterNewUser::handle
     */
    public function testHandle()
    {

        $emailSenderService = $this->getMockBuilder(Service::class)
            ->getMock();
        /* @var $emailSenderService Service */

        $activationTokenGenerator = $this->getMockBuilder(ActivationTokenGenerator::class)
            ->setMethods([
                'generate'
            ])
            ->getMock();
        $activationTokenGenerator->expects($this->once())
            ->method('generate')
            ->willReturn('activationToken');
        /* @var $activationTokenGenerator ActivationTokenGenerator */

        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $uuidGenerator Generator */

        $hashGenerator = $this->getMockBuilder(HashGenerator::class)
            ->setMethods([
                'hash'
            ])
            ->getMock();
        $hashGenerator->expects($this->once())
            ->method('hash')
            ->with('password')
            ->willReturn('passwordHash');
        /* @var $hashGenerator HashGenerator */

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */

        $command = new RegisterNewUserCommand(
            'user@email.com',
            'password',
            $emailSenderService,
            $activationTokenGenerator,
            $uuidGenerator,
            $hashGenerator,
            $email
        );

        $registerNewUser = new RegisterNewUser($uuidGenerator);
        $user = $registerNewUser->handle($command);
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('user@email.com', $user->getEmail());
        $this->assertSame('passwordHash', $user->getPasswordHash());
        $this->assertSame('activationToken', $user->getActivationToken());
        $sendEmailCommand = $registerNewUser->getNextCommands()->shift();
        $this->assertInstanceOf(SendEmail::class, $sendEmailCommand);
        /* @var $sendEmailCommand SendEmail */
        $this->assertSame($email, $sendEmailCommand->getEmail());
        $this->assertSame($emailSenderService, $sendEmailCommand->getEmailSenderService());
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $userHasBeenRegistered = $user->getUncommittedEvents()->shift();
        /* @var $userHasBeenRegistered UserHasBeenRegistered */
        $this->assertInstanceOf(UserHasBeenRegistered::class, $userHasBeenRegistered);
        $this->assertSame('user@email.com', $userHasBeenRegistered->getUserEmail());
        $this->assertSame('passwordHash', $userHasBeenRegistered->getPasswordHash());
        $activationTokenHasBeenGenerated = $user->getUncommittedEvents()->shift();
        /* @var $activationTokenHasBeenGenerated ActivationTokenHasBeenGenerated */
        $this->assertInstanceOf(ActivationTokenHasBeenGenerated::class, $activationTokenHasBeenGenerated);
        $this->assertSame('activationToken', $activationTokenHasBeenGenerated->getActivationToken());
        $this->assertSame('user@email.com', $activationTokenHasBeenGenerated->getUserEmail());
    }

}
