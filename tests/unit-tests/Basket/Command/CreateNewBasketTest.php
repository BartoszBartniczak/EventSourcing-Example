<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command;


use BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory;

class CreateNewBasketTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\CreateNewBasket::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\CreateNewBasket::getBasketFactory
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\CreateNewBasket::getUserEmail
     */
    public function testConstructor()
    {

        $factory = $this->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $factory Factory */

        $createNewBasket = new CreateNewBasket($factory, 'user@email.pl');
        $this->assertSame($factory, $createNewBasket->getBasketFactory());
        $this->assertSame('user@email.pl', $createNewBasket->getUserEmail());
    }

}
