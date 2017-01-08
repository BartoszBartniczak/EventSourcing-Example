<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;

use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\AddProductToTheBasket as AddProductToTheBasketCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenAddedToTheBasket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator;

class AddProductToTheBasketTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\AddProductToTheBasket::handle
     */
    public function testHandle()
    {
        $generator = $this->getMockBuilder(Generator::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $generator Generator */

        $basketId = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basketId Id */

        $basketMock = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $basketId,
                'owner@email'
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $basketMock Basket */

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $product Product */

        $addProductToTheBasketCommand = new AddProductToTheBasketCommand($basketMock, $product, 45.00);

        $addProductToTheBasket = new AddProductToTheBasket($generator);
        $basket = $addProductToTheBasket->handle($addProductToTheBasketCommand);
        $this->assertSame($basketMock, $basket);
        $this->assertEquals(0, $basket->getCommittedEvents()->count());
        $this->assertEquals(1, $basket->getUncommittedEvents()->count());
        $productHasBeenAddedToTheBasket = $basket->getUncommittedEvents()->shift();
        $this->assertInstanceOf(ProductHasBeenAddedToTheBasket::class, $productHasBeenAddedToTheBasket);
        /* @var $productHasBeenAddedToTheBasket ProductHasBeenAddedToTheBasket */
        $this->assertSame($basket, $productHasBeenAddedToTheBasket->getBasket());
        $this->assertSame($product, $productHasBeenAddedToTheBasket->getProduct());
        $this->assertSame(45.00, $productHasBeenAddedToTheBasket->getQuantity());
    }

}
