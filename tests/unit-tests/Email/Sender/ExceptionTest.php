<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Sender;


use BartoszBartniczak\EventSourcing\Shop\Email\Exception as EmailException;
use BartoszBartniczak\TestCase\ExceptionTestCase;

class ExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Sender\Exception::__construct
     */
    public function testConstructor()
    {

        $exception = new Exception();
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertNotInstanceOf(EmailException::class, $exception);
        $this->assertConstructorUsesStandardArguments(Exception::class);
        $this->assertConstructorDoesNotRequiredAnyArguments(Exception::class);
    }

}
