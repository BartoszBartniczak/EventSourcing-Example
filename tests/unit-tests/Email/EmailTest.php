<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email;

use BartoszBartniczak\EventSourcing\Shop\Email\Event\EmailHasBeenSent;
use BartoszBartniczak\EventSourcing\Shop\Email\Event\EmailHasNotBeenSent;


class EmailTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Email::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Email::getId()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Email::isSent()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Email::getUnsuccessfulAttemptsOfSending()
     */
    public function testConstructor()
    {

        $id = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $id Id */

        $email = new Email($id);
        $this->assertSame($id, $email->getId());
        $this->assertFalse($email->isSent());
        $this->assertEquals(0, $email->getUnsuccessfulAttemptsOfSending());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Email::handleEmailHasBeenSent
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Email::markAsSent()
     */
    public function testHandleEmailHasBeenSent()
    {

        $emailId = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $emailId Id */

        $email = $this->getMockBuilder(Email::class)
            ->setConstructorArgs([
                $emailId
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();

        $email->method('findHandleMethod')
            ->willReturn('handleEmailHasBeenSent');
        /* @var $email Email */

        $emailHasBeenSent = $this->getMockBuilder(EmailHasBeenSent::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $emailHasBeenSent EmailHasBeenSent */

        $email->apply($emailHasBeenSent);
        /* @var $emailHasBeenSent EmailHasBeenSent */
        $this->assertTrue($email->isSent());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Email::handleEmailHasNotBeenSent
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Email::getUnsuccessfulAttemptsOfSending()
     */
    public function testHandleEmailHasNotBeenSent()
    {

        $emailId = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $emailId Id */

        $emailHasNotBeenSent = $this->getMockBuilder(EmailHasNotBeenSent::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $emailHasNotBeenSent EmailHasNotBeenSent */

        $email = $this->getMockBuilder(Email::class)
            ->setConstructorArgs([
                $emailId
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();

        $email->method('findHandleMethod')
            ->willReturn('handleEmailHasNotBeenSent');
        /* @var $email Email */
        $email->apply($emailHasNotBeenSent);

        $this->assertEquals(1, $email->getUnsuccessfulAttemptsOfSending());
    }

}
