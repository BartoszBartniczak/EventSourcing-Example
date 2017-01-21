<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Repository;


use BartoszBartniczak\TestCase\ExceptionTestCase;

class ExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Repository\Exception::__construct
     */
    public function testConstructor()
    {

        $this->assertInstanceOf(\Exception::class, new Exception());
        $this->assertConstructorDoesNotRequiredAnyArguments(Exception::class);
        $this->assertConstructorUsesStandardArguments(Exception::class);
    }

}
