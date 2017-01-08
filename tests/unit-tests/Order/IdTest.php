<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order;


use BartoszBartniczak\EventSourcing\UUID\UUID;

class IdTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Id::__construct
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(UUID::class, new Id(uniqid()));
    }

}
