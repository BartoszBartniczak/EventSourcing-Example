<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;

use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\ChangeQuantityOfTheProduct as ChangeQuantityOfTheProductCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\QuantityOfTheProductHasBeenChanged;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position as BasketPosition;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;
use BartoszBartniczak\EventSourcing\UUID\Generator;

class ChangeQuantityOfTheProductTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\ChangeQuantityOfTheProduct::handle
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


        $productId = $this->getMockBuilder(ProductId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $productId ProductId */

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getId'
            ])
            ->getMock();
        $product->method('getId')
            ->willReturn($productId);
        /* @var $product Product */

        $basketPosition = $this->getMockBuilder(BasketPosition::class)
            ->setConstructorArgs([
                $productId,
                1.00
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $basketPosition BasketPosition */

        $basketMock = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $basketId,
                'owner@email.com'
            ])
            ->setMethods([
                'findPositionByProductId'
            ])
            ->getMock();

        $basketMock->method('findPositionByProductId')
            ->with($productId)
            ->willReturn($basketPosition);
        /* @var $basketMock Basket */


        $changeQuantityOfTheProductCommand = new ChangeQuantityOfTheProductCommand($basketMock, $productId, 12.56);
        $changeQuantityOfTheProduct = new ChangeQuantityOfTheProduct($generator);
        $basket = $changeQuantityOfTheProduct->handle($changeQuantityOfTheProductCommand);
        $this->assertSame($basketMock, $basket);
        $this->assertEquals(0, $basket->getCommittedEvents()->count());
        $this->assertEquals(1, $basket->getUncommittedEvents()->count());
        $quantityOfTheProductHasBeenChanged = $basket->getUncommittedEvents()->shift();
        $this->assertInstanceOf(QuantityOfTheProductHasBeenChanged::class, $quantityOfTheProductHasBeenChanged);
        /* @var $quantityOfTheProductHasBeenChanged QuantityOfTheProductHasBeenChanged */
        $this->assertSame($basketId, $quantityOfTheProductHasBeenChanged->getBasketId());
        $this->assertSame($productId, $quantityOfTheProductHasBeenChanged->getProductId());
        $this->assertSame(12.56, $quantityOfTheProductHasBeenChanged->getQuantity());
    }

}
