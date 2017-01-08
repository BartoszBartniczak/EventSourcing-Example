<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository;


use BartoszBartniczak\TestCase\ExceptionTestCase;

class ExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\Exception::__construct
     */
    public function testConstructor()
    {

        $exception = new Exception();
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertConstructorDoesNotRequiredAnyArguments(Exception::class);
        $this->assertConstructorUsesStandardArguments(Exception::class);
    }

}
