<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Password;


use BartoszBartniczak\EventSourcing\Shop\User\User;

class HashGenerator
{

    /**
     * @param $password
     * @param $salt
     * @param int $algorithm
     * @param int $cost
     * @return string
     */
    public function hash(string $password, int $algorithm = PASSWORD_DEFAULT, int $cost = 10): string
    {

        $params = ['cost' => $cost];

        return password_hash($password, $algorithm, $params);
    }


    public function verifyUserPassword(string $password, User $user): bool
    {
        return password_verify($password, $user->getPasswordHash());
    }

    /**
     * @param string $hash
     * @param int $algorithm
     * @param int $cost
     * @return bool
     */
    public function needsRehash(string $hash, int $algorithm = PASSWORD_DEFAULT, $cost = 10): bool
    {
        return password_needs_rehash($hash, $algorithm, ['cost' => $cost]);
    }

    /**
     * @param string $hash
     * @return HashInfo
     */
    public function hashInfo(string $hash): HashInfo
    {
        $info = password_get_info($hash);
        return new HashInfo($info['algo'], $info['algoName'], $info['options']['cost'], $info['options']['salt']??'');
    }

}