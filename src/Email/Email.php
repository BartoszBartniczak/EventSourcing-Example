<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email;


use BartoszBartniczak\EventSourcing\Shop\Email\Event\EmailHasBeenSent;
use BartoszBartniczak\EventSourcing\Shop\Email\Event\EmailHasNotBeenSent;
use BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\UUID\UUID;

class Email extends EventAggregate
{
    /**
     * @var UUID
     */
    private $id;

    /**
     * @var bool
     */
    private $sent;

    /**
     * @var
     */
    private $unsuccessfulAttemptsOfSending;

    /**
     * Email constructor.
     * @param Id $id
     */
    public function __construct(Id $id)
    {
        parent::__construct();
        $this->id = $id;
        $this->sent = false;
        $this->unsuccessfulAttemptsOfSending = 0;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->sent;
    }

    /**
     * @return mixed
     */
    public function getUnsuccessfulAttemptsOfSending()
    {
        return $this->unsuccessfulAttemptsOfSending;
    }

    public function handleEmailHasBeenSent(EmailHasBeenSent $event)
    {
        $this->markAsSent();
    }

    protected function markAsSent()
    {
        $this->sent = true;
    }

    public function handleEmailHasNotBeenSent(EmailHasNotBeenSent $event)
    {
        $this->unsuccessfulAttemptsOfSending++;
    }

}