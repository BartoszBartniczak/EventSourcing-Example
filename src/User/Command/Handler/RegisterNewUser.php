<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Email\Command\SendEmail as SendEmailCommand;
use BartoszBartniczak\EventSourcing\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser as RegisterNewUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Event\ActivationTokenHasBeenGenerated;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenRegistered;
use BartoszBartniczak\EventSourcing\Shop\User\User;

class RegisterNewUser extends CommandHandler
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param Command|RegisterNewUserCommand $command
     * @return EventAggregate;
     */
    public function handle(Command $command): User
    {
        $passwordHash = $command->getHashGenerator()->hash($command->getUserPassword());
        $this->user = new User($command->getUserEmail(), $passwordHash);
        $activationToken = $command->getActivationTokenGenerator()->generate();

        $this->user->apply(
            new UserHasBeenRegistered(
                $this->generateEventId(),
                $this->generateDateTime(),
                $this->user->getEmail(),
                $this->user->getPasswordHash()
            )
        );

        $this->user->apply(new ActivationTokenHasBeenGenerated(
            $this->generateEventId(),
            $this->generateDateTime(),
            $this->user->getEmail(),
            $activationToken
        ));

        $this->addNextCommand(new SendEmailCommand($command->getEmailSenderService(), $command->getEmail()));

        return $this->user;
    }

}