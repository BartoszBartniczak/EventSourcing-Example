<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Sender;


use BartoszBartniczak\TestCase\ExceptionTestCase;

class CannotSendEmailExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Sender\CannotSendEmailException::__construct
     */
    public function testConstructor()
    {

        $cannotSendEmailException = new CannotSendEmailException();
        $this->assertInstanceOf(Exception::class, $cannotSendEmailException);
        $this->assertConstructorDoesNotRequiredAnyArguments(CannotSendEmailException::class);
        $this->assertConstructorUsesStandardArguments(CannotSendEmailException::class);
    }

}
