<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\UserRepository;
use BartoszBartniczak\EventSourcing\Shop\User\User;

class LogOutUser implements Command
{

    /**
     * @var User
     */
    private $userEmail;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * LogOutUser constructor.
     * @param string $userEmail
     * @param UserRepository $userRepository
     */
    public function __construct(string $userEmail, UserRepository $userRepository)
    {
        $this->userEmail = $userEmail;
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
     * @return UserRepository
     */
    public function getUserRepository(): UserRepository
    {
        return $this->userRepository;
    }


}