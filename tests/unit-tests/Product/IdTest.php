<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product;


use BartoszBartniczak\EventSourcing\UUID\UUID;

class IdTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Id::__construct
     */
    public function testConstructor()
    {

        $id = new Id(uniqid());
        $this->assertInstanceOf(UUID::class, $id);

    }

}
