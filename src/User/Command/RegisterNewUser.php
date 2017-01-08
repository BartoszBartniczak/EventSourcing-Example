<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Command;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Email\Sender\Service as EmailSenderService;
use BartoszBartniczak\EventSourcing\Shop\Generator\ActivationTokenGenerator;
use BartoszBartniczak\EventSourcing\Shop\Password\HashGenerator;
use BartoszBartniczak\EventSourcing\Shop\Password\SaltGenerator;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator as UUIDGenerator;

class RegisterNewUser implements Command
{

    /**
     * @var EmailSenderService
     */
    private $emailSenderService;

    /**
     * @var $userEmail
     */
    private $userEmail;

    /**
     * @var string
     */
    private $userPassword;

    /**
     * @var ActivationTokenGenerator;
     */
    private $activationTokenGenerator;

    /**
     * @var UUIDGenerator
     */
    private $uuidGenerator;

    /**
     * @var HashGenerator
     */
    private $hashGenerator;
    /**
     * @var Email
     */
    private $email;

    /**
     * RegisterNewUser constructor.
     * @param string $userEmail
     * @param string $userPassword
     * @param EmailSenderService $emailSenderService
     * @param ActivationTokenGenerator $activationTokenGenerator
     * @param UUIDGenerator $generator
     * @param HashGenerator $hashGenerator
     * @param Email $email
     * @internal param SaltGenerator $saltGenerator
     */
    public function __construct(string $userEmail, string $userPassword, EmailSenderService $emailSenderService, ActivationTokenGenerator $activationTokenGenerator, UUIDGenerator $generator, HashGenerator $hashGenerator, Email $email)
    {
        $this->emailSenderService = $emailSenderService;
        $this->userEmail = $userEmail;
        $this->activationTokenGenerator = $activationTokenGenerator;
        $this->uuidGenerator = $generator;
        $this->userPassword = $userPassword;
        $this->hashGenerator = $hashGenerator;
        $this->email = $email;
    }

    /**
     * @return EmailSenderService
     */
    public function getEmailSenderService(): EmailSenderService
    {
        return $this->emailSenderService;
    }

    /**
     * @return mixed
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @return ActivationTokenGenerator
     */
    public function getActivationTokenGenerator(): ActivationTokenGenerator
    {
        return $this->activationTokenGenerator;
    }

    /**
     * @return UUIDGenerator
     */
    public function getUuidGenerator(): UUIDGenerator
    {
        return $this->uuidGenerator;
    }

    /**
     * @return HashGenerator
     */
    public function getHashGenerator(): HashGenerator
    {
        return $this->hashGenerator;
    }

    /**
     * @return string
     */
    public function getUserPassword(): string
    {
        return $this->userPassword;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }


}