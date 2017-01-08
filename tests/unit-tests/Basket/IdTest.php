<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket;


use BartoszBartniczak\EventSourcing\UUID\UUID;

class IdTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Id::__construct
     */
    public function testConstructor()
    {

        $this->assertInstanceOf(UUID::class, new Id(uniqid()));

    }

}
