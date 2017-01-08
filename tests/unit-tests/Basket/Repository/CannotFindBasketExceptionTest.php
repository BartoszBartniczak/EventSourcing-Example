<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Repository;


use BartoszBartniczak\TestCase\ExceptionTestCase;

class CannotFindBasketExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Repository\CannotFindBasketException::__construct
     */
    public function testConstructor()
    {

        $this->assertInstanceOf(\InvalidArgumentException::class, new CannotFindBasketException());
        $this->assertConstructorDoesNotRequiredAnyArguments(CannotFindBasketException::class);
        $this->assertConstructorUsesStandardArguments(CannotFindBasketException::class);
    }

}
