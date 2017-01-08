<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository;


use BartoszBartniczak\TestCase\ExceptionTestCase;

class CannotFindProductExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\CannotFindProductException::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Exception::class, new CannotFindProductException());
        $this->assertConstructorUsesStandardArguments(CannotFindProductException::class);
        $this->assertConstructorDoesNotRequiredAnyArguments(CannotFindProductException::class);
    }

}
