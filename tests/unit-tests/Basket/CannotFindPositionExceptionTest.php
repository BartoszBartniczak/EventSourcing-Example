<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket;


use BartoszBartniczak\TestCase\ExceptionTestCase;

class CannotFindPositionExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\CannotFindPositionException::__construct
     */
    public function testConstructor()
    {

        $this->assertInstanceOf(Exception::class, new CannotFindPositionException());
        $this->assertConstructorDoesNotRequiredAnyArguments(CannotFindPositionException::class);
        $this->assertConstructorUsesStandardArguments(CannotFindPositionException::class);
    }

}
