<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\UUID;


class UUIDTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\UUID\UUID::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\UUID\UUID::toNative()
     */
    public function testToNative()
    {
        $uuid = new UUID('d6a4d4f7-e446-4a49-b5fe-b5d9df85811a');
        $this->assertEquals('d6a4d4f7-e446-4a49-b5fe-b5d9df85811a', $uuid->toNative());
    }

}
