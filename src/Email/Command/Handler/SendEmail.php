<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Email\Event\EmailHasBeenSent;
use BartoszBartniczak\EventSourcing\Shop\Email\Event\EmailHasNotBeenSent;
use BartoszBartniczak\EventSourcing\Shop\Email\Sender\CannotSendEmailException;
use BartoszBartniczak\EventSourcing\Shop\Email\Sender\Exception;

class SendEmail extends CommandHandler
{

    /**
     * @var Exception
     */
    private $exception;

    /**
     * @param Command|\Shop\Email\Command\SendEmail $command
     * @return Email
     */
    public function handle(Command $command): Email
    {
        try {
            $command->getEmailSenderService()->send($command->getEmail());
        } catch (CannotSendEmailException $cannotSendEmailException) {
            $this->exception = $cannotSendEmailException;
        }

        if ($this->isSent()) {
            $command->getEmail()->apply(new EmailHasBeenSent(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getEmail()
            ));
        } else {
            $command->getEmail()->apply(new EmailHasNotBeenSent(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getEmail(),
                $this->exception->getMessage()
            ));
        }

        return $command->getEmail();
    }

    private function isSent(): bool
    {
        return !$this->exception instanceof Exception;
    }

}