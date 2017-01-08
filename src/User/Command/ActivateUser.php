<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\UserRepository;
use BartoszBartniczak\EventSourcing\Shop\User\User;

class ActivateUser implements Command
{

    /**
     * @var string
     */
    protected $userEmail;

    /**
     * @var string
     */
    protected $activationToken;

    /**
     * @var UserRepository
     */
    protected $userRepository;


    /**
     * ActivateUser constructor.
     * @param User $user
     * @param string $activationToken
     */
    public function __construct(string $userEmail, string $activationToken, UserRepository $userRepository)
    {
        $this->userEmail = $userEmail;
        $this->activationToken = $activationToken;
        $this->userRepository = $userRepository;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    /**
     * @return string
     */
    public function getActivationToken(): string
    {
        return $this->activationToken;
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository(): UserRepository
    {
        return $this->userRepository;
    }

}