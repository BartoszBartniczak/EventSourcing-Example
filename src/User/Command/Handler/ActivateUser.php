<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\User\Command\ActivateUser as ActivateUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Event\AttemptOfActivatingAlreadyActivatedAccount;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount as UnsuccessfulAttemptOfActivatingUserAccountEvent;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserAccountHasBeenActivated as UserAccountHasBeenActivatedEvent;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\CannotFindUserException;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\UserRepository;
use BartoszBartniczak\EventSourcing\Shop\User\User;

class ActivateUser extends CommandHandler
{
    /**
     * @var string
     */
    private $activationToken;

    /**
     * @var User
     */
    private $userEmail;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var bool;
     */
    private $isTokenValid;

    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @var User
     */
    private $user;

    /**
     * @param Command|ActivateUserCommand $command
     * @return User
     * @throws CannotFindUserException
     */
    public function handle(Command $command): User
    {
        $this->userEmail = $command->getUserEmail();
        $this->activationToken = $command->getActivationToken();
        $this->userRepository = $command->getUserRepository();
        $this->user = $this->userRepository->findUserByEmail($this->userEmail);

        if ($this->user->isActive() === true) {
            $this->user->apply(
                new AttemptOfActivatingAlreadyActivatedAccount(
                    $this->generateEventId(),
                    $this->generateDateTime(),
                    $this->userEmail,
                    $this->activationToken,
                    'User has been already activated.'
                )
            );
        } elseif ($this->isTokenValid()) {
            $this->user->apply(new UserAccountHasBeenActivatedEvent(
                $this->generateEventId(),
                $this->generateDateTime(),
                $this->userEmail,
                $this->activationToken
            ));
        } else {
            $this->user->apply(new UnsuccessfulAttemptOfActivatingUserAccountEvent(
                    $this->generateEventId(),
                    $this->generateDateTime(),
                    $this->userEmail,
                    $this->activationToken,
                    'Invalid activation token.'
                )
            );
        }

        return $this->user;
    }

    private function isTokenValid(): bool
    {
        return $this->user->getActivationToken() === $this->activationToken;
    }


}