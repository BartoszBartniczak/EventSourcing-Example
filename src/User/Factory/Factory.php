<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Factory;


use BartoszBartniczak\EventSourcing\Shop\User\User;

class Factory
{

    public function createEmpty(): User
    {
        return new User('', '');
    }

    public function createNew(string $email, string $passwordHash): User
    {
        return new User($email, $passwordHash);
    }

}