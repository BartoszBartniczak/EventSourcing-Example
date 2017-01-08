<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Sender;


use BartoszBartniczak\EventSourcing\Shop\Email\Email;

interface Service
{

    /**
     * @param Email $email
     * @return void
     * @throws CannotSendEmailException
     */
    public function send(Email $email);

}