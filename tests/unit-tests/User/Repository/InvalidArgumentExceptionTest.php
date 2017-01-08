<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Repository;


use BartoszBartniczak\TestCase\ExceptionTestCase;

class InvalidArgumentExceptionTestCase extends ExceptionTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Repository\InvalidArgumentException::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(\InvalidArgumentException::class, new InvalidArgumentException());
        $this->assertConstructorDoesNotRequiredAnyArguments(InvalidArgumentException::class);
        $this->assertConstructorUsesStandardArguments(InvalidArgumentException::class);
    }


}
