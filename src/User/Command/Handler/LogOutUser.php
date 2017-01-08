<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenLoggedOut as UserHasBeenLoggedOutEvent;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\CannotFindUserException;
use BartoszBartniczak\EventSourcing\Shop\User\User;

class LogOutUser extends CommandHandler
{
    /**
     * @inheritDoc
     * @throws CannotFindUserException
     */
    public function handle(Command $command): User
    {
        /* @var $command \Shop\User\Command\LogOutUser */

        $user = $command->getUserRepository()->findUserByEmail($command->getUserEmail());

        $user->apply(
            new UserHasBeenLoggedOutEvent(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getUserEmail()
            )
        );

        return $user;
    }

}