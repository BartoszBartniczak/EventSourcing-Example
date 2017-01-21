<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\CQRS\Command\Handler\CannotHandleTheCommandException;
use BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\User\Event\AttemptOfLoggingInToInactiveAccount;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UnsuccessfulAttemptOfLoggingIn;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenLoggedIn;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\CannotFindUserException;
use BartoszBartniczak\EventSourcing\Shop\User\User;

class LogInUser extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): User
    {
        /* @var $command \BartoszBartniczak\EventSourcing\Shop\User\Command\LogInUser */

        try {
            $user = $command->getUserRepository()->findUserByEmail($command->getUserEmail());
        } catch (CannotFindUserException $cannotFindUserException) {
            throw new CannotHandleTheCommandException('User does not exist.', null, $cannotFindUserException);
        }

        if ($user->isActive() === false) {
            $user->apply(
                new AttemptOfLoggingInToInactiveAccount(
                    $this->generateEventId(),
                    $this->generateDateTime(),
                    $command->getUserEmail()
                )
            );
        } elseif ($command->getHashGenerator()->verifyUserPassword($command->getPassword(), $user)) {
            $user->apply(new UserHasBeenLoggedIn(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getUserEmail()
            ));
        } else {
            $user->apply(
                new UnsuccessfulAttemptOfLoggingIn(
                    $this->generateEventId(),
                    $this->generateDateTime(),
                    $command->getUserEmail()
                )
            );
        }

        return $user;
    }

}