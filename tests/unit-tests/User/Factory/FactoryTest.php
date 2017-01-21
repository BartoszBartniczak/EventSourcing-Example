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

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Factory\Factory::createNew
     */
    public function testCreateNew()
    {
        $factory = new Factory();
        $user = $factory->createNew('test@email.pl', 'passwordHash');
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('test@email.pl', $user->getEmail());
        $this->assertSame('passwordHash', $user->getPasswordHash());
    }

}
