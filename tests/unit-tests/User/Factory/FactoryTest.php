<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Factory;


use BartoszBartniczak\EventSourcing\Shop\User\User;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Factory\Factory::createEmpty
     */
    public function testCreateEmpty()
    {
        $factory = new Factory();
        $user = $factory->createEmpty();
        $this->assertInstanceOf(User::class, $user);
    }

}
