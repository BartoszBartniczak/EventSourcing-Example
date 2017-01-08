<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Sender;


use BartoszBartniczak\EventSourcing\Shop\Email\Email;

class NullEmailSenderService implements Service
{

    /**
     * @var bool
     */
    private $throwCannotSendEmailException;

    /**
     * NullEmailSenderService constructor.
     * @param bool $throwCannotSendEmailException
     */
    public function __construct($throwCannotSendEmailException = false)
    {
        $this->throwCannotSendEmailException = $throwCannotSendEmailException;
    }

    /**
     * @param Email $email
     * @throws CannotSendEmailException
     */
    public function send(Email $email)
    {
        if ($this->throwCannotSendEmailException) {
            throw new CannotSendEmailException("You are using NullEmailSenderService!");
        }
    }

}