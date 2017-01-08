<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Sender;


use BartoszBartniczak\EventSourcing\Shop\Email\Email;

class NullEmailSenderServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Sender\NullEmailSenderService::send
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Sender\NullEmailSenderService::__construct
     */
    public function testSendDoesNotThrowExceptionIfSwitchIsOff()
    {
        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */
        $nullEmailSenderService = new NullEmailSenderService();
        $nullEmailSenderService->send($email);
        $this->assertInstanceOf(Service::class, $nullEmailSenderService);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Sender\NullEmailSenderService::send
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Sender\NullEmailSenderService::__construct
     */
    public function testSendThrowsExceptionIfSwitchIsOff()
    {
        $this->expectException(CannotSendEmailException::class);
        $this->expectExceptionMessage('You are using NullEmailSenderService!');

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */
        $nullEmailSenderService = new NullEmailSenderService(true);
        $nullEmailSenderService->send($email);
    }

}
