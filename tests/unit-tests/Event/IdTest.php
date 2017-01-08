<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event;


use BartoszBartniczak\EventSourcing\Shop\UUID\UUID;

class IdTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $eventId = new Id(uniqid());
        $this->assertInstanceOf(UUID::class, $eventId);
    }

}
