<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email;


use BartoszBartniczak\EventSourcing\Shop\UUID\UUID;

class IdTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Id::__construct
     */
    public function testConstructor()
    {
        $id = new Id(uniqid());
        $this->assertInstanceOf(UUID::class, $id);
    }

}
