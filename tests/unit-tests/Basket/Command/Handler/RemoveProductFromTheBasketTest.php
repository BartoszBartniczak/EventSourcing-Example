<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;

use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\RemoveProductFromTheBasket as RemoveProductFromTheBasketCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenRemovedFromTheBasket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position as BasketPosition;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator;

class RemoveProductFromTheBasketTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\RemoveProductFromTheBasket::handle
     */
    public function testHandle()
    {

        $productId = $this->getMockBuilder(ProductId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $productId ProductId */

        $basketId = $this->getMockBuilder(BasketId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basketId BasketId */

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $product Product */

        $basketPosition = $this->getMockBuilder(BasketPosition::class)
            ->setConstructorArgs([
                $product,
                1.00
            ])
            ->getMock();
        /* @var $basketPosition BasketPosition */

        $basketMock = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $basketId,
                'owner@email.com'
            ])
            ->setMethods([
                'findPositionByProductId',
                'getPositions'
            ])
            ->getMock();
        $basketMock->method('findPositionByProductId')
            ->with($productId)
            ->willReturn($basketPosition);
        $basketMock->expects($this->once())
            ->method('getPositions');
        /* @var $basketMock Basket */

        $removeProductFromTheBasketCommand = new RemoveProductFromTheBasketCommand($basketMock, $productId);

        $generator = $this->getMockBuilder(Generator::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $generator Generator */

        $removeProductFromTheBasket = new RemoveProductFromTheBasket($generator);
        $basket = $removeProductFromTheBasket->handle($removeProductFromTheBasketCommand);
        $this->assertSame($basketMock, $basket);
        $this->assertEquals(0, $basket->getCommittedEvents()->count());
        $this->assertEquals(1, $basket->getUncommittedEvents()->count());
        $productHasBeenRemovedFromBasket = $basket->getUncommittedEvents()->shift();
        $this->assertInstanceOf(ProductHasBeenRemovedFromTheBasket::class, $productHasBeenRemovedFromBasket);
        /* @var $productHasBeenRemovedFromBasket ProductHasBeenRemovedFromTheBasket */
        $this->assertSame($basket, $productHasBeenRemovedFromBasket->getBasket());
        $this->assertSame($productId, $productHasBeenRemovedFromBasket->getProductId());
    }

}
