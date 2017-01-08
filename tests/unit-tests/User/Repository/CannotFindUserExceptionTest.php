<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Repository;


use BartoszBartniczak\EventSourcing\Shop\ExceptionTestCase;

class CannotFindUserExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Repository\CannotFindUserException::__construct
     */
    public function testConstructor()
    {

        $this->assertInstanceOf(InvalidArgumentException::class, new CannotFindUserException());
        $this->assertConstructorDoesNotRequiredAnyArguments(CannotFindUserException::class);
        $this->assertConstructorUsesStandardArguments(CannotFindUserException::class);
    }

}
