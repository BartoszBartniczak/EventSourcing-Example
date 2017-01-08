<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UnsuccessfulAttemptOfLoggingIn;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenLoggedIn;
use BartoszBartniczak\EventSourcing\Shop\User\User;

class LogInUser extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): User
    {
        /* @var $command \Shop\User\Command\LogInUser */

        $user = $command->getUserRepository()->findUserByEmail($command->getUserEmail());
        $loginStatus = $command->getHashGenerator()->verifyUserPassword($command->getPassword(), $user);

        if ($loginStatus === true) {
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