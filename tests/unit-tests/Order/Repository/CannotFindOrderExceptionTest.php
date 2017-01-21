<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Repository;


use BartoszBartniczak\TestCase\ExceptionTestCase;

class CannotFindOrderExceptionTest extends ExceptionTestCase
{

    public function testConstructor()
    {
        $this->assertInstanceOf(Exception::class, new CannotFindOrderException());
        $this->assertConstructorDoesNotRequiredAnyArguments(CannotFindOrderException::class);
        $this->assertConstructorUsesStandardArguments(CannotFindOrderException::class);
    }

}
