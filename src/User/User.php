<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User;


use BartoszBartniczak\ArrayObject\ArrayObject;
use BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\User\Event\ActivationTokenHasBeenGenerated;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UnsuccessfulAttemptOfLoggingIn;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserAccountHasBeenActivated;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenLoggedIn;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenLoggedOut;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenRegistered;

class User extends EventAggregate
{

    /**
     * The email is the ID of the User
     * @var string
     */
    private $email;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var string
     */
    private $activationToken;

    /**
     * @var string
     */
    private $passwordHash;

    /**
     * @var ArrayObject
     */
    private $loginDates;

    /**
     * @var int
     */
    private $unsuccessfulAttemptsOfActivatingUserAccount;

    /**
     * @var int
     */
    private $unsuccessfulAttemptsOfLoggingIn;

    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return mixed
     */
    public function getActivationToken()
    {
        return $this->activationToken;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getLoginDates(): ArrayObject
    {
        return $this->loginDates;
    }

    /**
     * @return int
     */
    public function getUnsuccessfulAttemptsOfActivatingUserAccount(): int
    {
        return $this->unsuccessfulAttemptsOfActivatingUserAccount;
    }

    /**
     * @return int
     */
    public function getUnsuccessfulAttemptsOfLoggingIn(): int
    {
        return $this->unsuccessfulAttemptsOfLoggingIn;
    }

    /**
     * @param UserHasBeenRegistered $event
     */
    protected function handleUserHasBeenRegistered(UserHasBeenRegistered $event)
    {
        $this->__construct($event->getUserEmail(), $event->getPasswordHash());
    }

    /**
     * User constructor.
     * @param string $email
     * @param string $passwordHash
     * @param string $passwordSalt
     */
    public function __construct(string $email, string $passwordHash)
    {
        parent::__construct();

        $this->email = $email;
        $this->passwordHash = $passwordHash;

        $this->active = false;
        $this->loginDates = new ArrayObject();
        $this->unsuccessfulAttemptsOfActivatingUserAccount = 0;
        $this->unsuccessfulAttemptsOfLoggingIn = 0;
    }

    /**
     * @param ActivationTokenHasBeenGenerated $event
     */
    protected function handleActivationTokenHasBeenGenerated(ActivationTokenHasBeenGenerated $event)
    {
        $this->changeActivationToken($event->getActivationToken());
    }

    /**
     * @param string $newToken
     */
    private function changeActivationToken(string $newToken)
    {
        $this->activationToken = $newToken;
    }

    /**
     * @param UserAccountHasBeenActivated $event
     */
    protected function handleUserAccountHasBeenActivated(UserAccountHasBeenActivated $event)
    {
        $this->activate();
    }

    /**
     * @return void
     */
    private function activate()
    {
        $this->active = true;
    }

    /**
     * @param UnsuccessfulAttemptOfActivatingUserAccount $event
     */
    protected function handleUnsuccessfulAttemptOfActivatingUserAccount(UnsuccessfulAttemptOfActivatingUserAccount $event)
    {
        $this->unsuccessfulAttemptsOfActivatingUserAccount++;
    }

    /**
     * @param UserHasBeenLoggedIn $event
     */
    protected function handleUserHasBeenLoggedIn(UserHasBeenLoggedIn $event)
    {
        $this->loginDates[] = $event->getDateTime();
    }

    protected function handleUserHasBeenLoggedOut(UserHasBeenLoggedOut $event)
    {

    }

    protected function handleUnsuccessfulAttemptOfLoggingIn(UnsuccessfulAttemptOfLoggingIn $unsuccessfulAttemptOfLoggingIn)
    {
        $this->unsuccessfulAttemptsOfLoggingIn++;
    }

}