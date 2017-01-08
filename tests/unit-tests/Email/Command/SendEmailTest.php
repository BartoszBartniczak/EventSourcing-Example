<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Command;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Email\Sender\Service;

class SendEmailTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Command\SendEmail::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Command\SendEmail::getEmail()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Command\SendEmail::getEmailSenderService()
     */
    public function testGetters()
    {
        $service = $this->getMockBuilder(Service::class)
            ->getMockForAbstractClass();
        /* @var $service Service */

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */

        $sendEmail = new SendEmail($service, $email);
        $this->assertInstanceOf(Command::class, $sendEmail);
        $this->assertSame($service, $sendEmail->getEmailSenderService());
        $this->assertSame($email, $sendEmail->getEmail());
    }

}
