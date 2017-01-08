<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop;

abstract class ExceptionTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $className
     */
    public function assertConstructorDoesNotRequiredAnyArguments(string $className)
    {
        new $className();
    }

    /**
     * @param string $className
     */
    public function assertConstructorUsesStandardArguments(string $className)
    {
        $message = uniqid();
        $code = random_int(0, 100);
        $previous = new \Exception();

        $exception = new $className($message, $code, $previous);
        /* @var $exception \Exception */
        self::assertSame($message, $exception->getMessage());
        self::assertSame($code, $exception->getCode());
        self::assertSame($previous, $exception->getPrevious());
    }

}