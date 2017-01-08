<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Command;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Email\Sender\Service as EmailSenderService;

class SendEmail implements Command
{

    /**
     * @var EmailSenderService
     */
    protected $emailSenderService;

    /**
     * @var Email
     */
    protected $email;

    /**
     * SendEmail constructor.
     * @param EmailSenderService $emailSenderService
     * @param Email $email
     */
    public function __construct(EmailSenderService $emailSenderService, Email $email)
    {
        $this->emailSenderService = $emailSenderService;
        $this->email = $email;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return EmailSenderService
     */
    public function getEmailSenderService(): EmailSenderService
    {
        return $this->emailSenderService;
    }

}