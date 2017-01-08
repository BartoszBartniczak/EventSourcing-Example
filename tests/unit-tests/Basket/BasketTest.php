<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket;


use BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenClosed;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenAddedToTheBasket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenRemovedFromTheBasket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\QuantityOfTheProductHasBeenChanged;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position as BasketPosition;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\PositionArray;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;

class BasketTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::getId()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::getOwnerEmail()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::getPositions()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::isOpen()
     */
    public function testConstructor()
    {
        $basketId = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basketId Id */

        $basket = new Basket($basketId, 'test@email.com');
        $this->assertSame($basketId, $basket->getId());
        $this->assertSame('test@email.com', $basket->getOwnerEmail());
        $this->assertInstanceOf(PositionArray::class, $basket->getPositions());
        $this->assertEquals(0, $basket->getPositions()->count());
        $this->assertTrue($basket->isOpen());
        $this->assertEquals(0, $basket->getCommittedEvents()->count());
        $this->assertEquals(0, $basket->getUncommittedEvents()->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::handleBasketHasBeenCreated
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::__construct
     */
    public function testHandleBasketHasBeenCreated()
    {
        $emptyBasket = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();

        $emptyBasket->method('findHandleMethod')
            ->willReturn('handleBasketHasBeenCreated');
        /* @var $emptyBasket Basket */

        $id = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $id Id */

        $basket = new Basket($id, 'owner@email.com');
        /* @var $basket Basket */

        $basketHasBeenCreated = $this->getMockBuilder(BasketHasBeenCreated::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getBasket',
            ])
            ->getMock();
        $basketHasBeenCreated->method('getBasket')
            ->willReturn($basket);


        /* @var $basketHasBeenCreated BasketHasBeenCreated */
        $emptyBasket->apply($basketHasBeenCreated);
        $this->assertSame($id, $emptyBasket->getId());
        $this->assertSame('owner@email.com', $emptyBasket->getOwnerEmail());
        $this->assertEquals(0, $emptyBasket->getCommittedEvents()->count());
        $this->assertEquals(1, $emptyBasket->getUncommittedEvents()->count());
        $this->assertSame($basketHasBeenCreated, $emptyBasket->getUncommittedEvents()->shift());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::handleProductHasBeenAddedToTheBasket
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::add
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::createNewItem
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::findPositionByProductId
     */
    public function testHandleProductHasBeenAddedToTheBasket()
    {

        $basketId = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basketId Id */

        $basket = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $basketId,
                'email@owner.pl'
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();

        $basket->method('findHandleMethod')
            ->willReturn('handleProductHasBeenAddedToTheBasket');
        /* @var $basket Basket */

        $productId = $this->getMockBuilder(ProductId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $productId ProductId */

        $product1 = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getId'
            ])
            ->getMock();

        $product1->method('getId')
            ->willReturn($productId);
        /* @var $product1 Product */

        $productHasBeenAddedToTheBasket1 = $this->getMockBuilder(ProductHasBeenAddedToTheBasket::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getProduct',
                'getQuantity'
            ])
            ->getMock();

        $productHasBeenAddedToTheBasket1->method('getProduct')
            ->willReturn($product1);

        $productHasBeenAddedToTheBasket1->method('getQuantity')
            ->willReturn(12.03);
        /* @var $productHasBeenAddedToTheBasket1 ProductHasBeenAddedToTheBasket */

        $basket->apply($productHasBeenAddedToTheBasket1);
        $this->assertEquals(1, $basket->getPositions()->count());
        $position = $basket->findPositionByProductId($productId);
        $this->assertSame(12.03, $position->getQuantity());
        $this->assertSame($product1, $position->getProduct());
        $this->assertEquals(0, $basket->getCommittedEvents()->count());
        $this->assertEquals(1, $basket->getUncommittedEvents()->count());
        $this->assertSame($productHasBeenAddedToTheBasket1, $basket->getUncommittedEvents()->offsetGet(0));

        $productHasBeenAddedToTheBasket2 = $this->getMockBuilder(ProductHasBeenAddedToTheBasket::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getProduct',
                'getQuantity'
            ])
            ->getMock();

        $productHasBeenAddedToTheBasket2->method('getProduct')
            ->willReturn($product1);

        $productHasBeenAddedToTheBasket2->method('getQuantity')
            ->willReturn(7.04);
        /* @var $productHasBeenAddedToTheBasket2 ProductHasBeenAddedToTheBasket */

        $basket->apply($productHasBeenAddedToTheBasket2);
        $this->assertEquals(1, $basket->getPositions()->count());
        $position = $basket->findPositionByProductId($productId);
        $this->assertSame(19.07, $position->getQuantity());
        $this->assertSame($product1, $position->getProduct());
        $this->assertEquals(0, $basket->getCommittedEvents()->count());
        $this->assertEquals(2, $basket->getUncommittedEvents()->count());
        $this->assertSame($productHasBeenAddedToTheBasket2, $basket->getUncommittedEvents()->offsetGet(1));

    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::handleQuantityOfTheProductHasBeenChanged
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::changeQuantity
     */
    public function testHandleQuantityOfTheProductHasBeenChanged()
    {

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $product Product */

        $basketPosition = $this->getMockBuilder(BasketPosition::class)
            ->setConstructorArgs([
                $product,
                1.0
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $basketPosition BasketPosition */

        $id = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $id Id */

        $productId = $this->getMockBuilder(ProductId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $productId ProductId */

        $basket = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $id,
                'owner@owner.pl'
            ])
            ->setMethods([
                'findHandleMethod',
                'findPositionByProductId'
            ])
            ->getMock();
        $basket->method('findHandleMethod')
            ->willReturn('handleQuantityOfTheProductHasBeenChanged');
        $basket->method('findPositionByProductId')
            ->with($productId)
            ->willReturn($basketPosition);
        /* @var $basket Basket */

        $quantityOfTheProductHasBeenChanged = $this->getMockBuilder(QuantityOfTheProductHasBeenChanged::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getProductId',
                'getQuantity'
            ])
            ->getMock();

        $quantityOfTheProductHasBeenChanged->method('getQuantity')
            ->willReturn(13.00);

        $quantityOfTheProductHasBeenChanged->method('getProductId')
            ->willReturn($productId);
        /* @var $quantityOfTheProductHasBeenChanged QuantityOfTheProductHasBeenChanged */

        $basket->apply($quantityOfTheProductHasBeenChanged);
        $this->assertSame(13.00, $basketPosition->getQuantity());
        $this->assertEquals(0, $basket->getCommittedEvents()->count());
        $this->assertEquals(1, $basket->getUncommittedEvents()->count());
        $this->assertSame($quantityOfTheProductHasBeenChanged, $basket->getUncommittedEvents()->shift());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::changeQuantity
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::handleQuantityOfTheProductHasBeenChanged
     */
    public function testChangeQuantityThrowsExceptionIfPositionDoesNotExist()
    {
        $this->expectException(CannotFindPositionException::class);
        $this->expectExceptionMessage("Cannot find position with product id: 'ba855eda-6daa-47ed-9fe5-d1e6a5b15ea4'");

        $id = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $id Id */

        $basket = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $id,
                'owner@owner.pl'
            ])
            ->setMethods([
                'findHandleMethod',
            ])
            ->getMock();
        $basket->method('findHandleMethod')
            ->willReturn('handleQuantityOfTheProductHasBeenChanged');
        /* @var $basket Basket */

        $productId = $this->getMockBuilder(ProductId::class)
            ->setConstructorArgs([
                'ba855eda-6daa-47ed-9fe5-d1e6a5b15ea4'
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $productId ProductId */

        $quantityOfTheProductHasBeenChanged = $this->getMockBuilder(QuantityOfTheProductHasBeenChanged::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getProductId',
                'getQuantity'
            ])
            ->getMock();
        $quantityOfTheProductHasBeenChanged->method('getQuantity')
            ->willReturn(1.0);
        $quantityOfTheProductHasBeenChanged->method('getProductId')
            ->willReturn($productId);
        /* @var $quantityOfTheProductHasBeenChanged QuantityOfTheProductHasBeenChanged */

        $basket->apply($quantityOfTheProductHasBeenChanged);

    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::handleProductHasBeenRemovedFromTheBasket
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::remove
     */
    public function testHandleProductHasBeenRemovedFromTheBasket()
    {
        $productId = new ProductId('7c204e0a-5141-4fa8-bbd1-d59443bcc73f');

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getId'
            ])
            ->getMock();

        $product->method('getId')
            ->willReturn($productId);
        /* @var $product Product */

        $productHasBeenAddedToTheBasket = $this->getMockBuilder(ProductHasBeenAddedToTheBasket::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getProduct',
                'getQuantity'
            ])
            ->getMock();
        $productHasBeenAddedToTheBasket->method('getQuantity')
            ->willReturn(94.00);
        $productHasBeenAddedToTheBasket->method('getProduct')
            ->willReturn($product);
        /* @var $productHasBeenAddedToTheBasket ProductHasBeenAddedToTheBasket */

        $productHasBeenRemovedFromTheBasket = $this->getMockBuilder(ProductHasBeenRemovedFromTheBasket::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getProductId'
            ])
            ->getMock();
        $productHasBeenRemovedFromTheBasket->method('getProductId')
            ->willReturn($productId);
        /* @var $productHasBeenRemovedFromTheBasket ProductHasBeenRemovedFromTheBasket */

        $id = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $id Id */

        $basket = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $id,
                'email@owner.com'
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();

        $basket->method('findHandleMethod')
            ->willReturnMap([
                [$productHasBeenAddedToTheBasket, 'handleProductHasBeenAddedToTheBasket'],
                [$productHasBeenRemovedFromTheBasket, 'handleProductHasBeenRemovedFromTheBasket']
            ]);
        /* @var $basket Basket */
        $basket->apply($productHasBeenAddedToTheBasket);
        $this->assertEquals(1, $basket->getPositions()->count());
        $this->assertEquals(1, $basket->getUncommittedEvents()->count());

        $basket->apply($productHasBeenRemovedFromTheBasket);
        $this->assertEquals(0, $basket->getPositions()->count());
        $this->assertEquals(2, $basket->getUncommittedEvents()->count());
        $this->assertSame($productHasBeenRemovedFromTheBasket, $basket->getUncommittedEvents()->offsetGet(1));
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::handleProductHasBeenRemovedFromTheBasket
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::remove
     */
    public function testRemoveThrowsExceptionIfPositionDoesNotExist()
    {
        $this->expectException(CannotFindPositionException::class);
        $this->expectExceptionMessage("Cannot find position with product id: '7c204e0a-5141-4fa8-bbd1-d59443bcc73f'");

        $productId = new ProductId('7c204e0a-5141-4fa8-bbd1-d59443bcc73f');

        $productHasBeenRemovedFromTheBasket = $this->getMockBuilder(ProductHasBeenRemovedFromTheBasket::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getProductId'
            ])
            ->getMock();
        $productHasBeenRemovedFromTheBasket->method('getProductId')
            ->willReturn($productId);
        /* @var $productHasBeenRemovedFromTheBasket ProductHasBeenRemovedFromTheBasket */

        $id = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $id Id */

        $basket = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $id,
                'email@owner.com'
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $basket->method('findHandleMethod')
            ->willReturn('handleProductHasBeenRemovedFromTheBasket');
        /* @var $basket Basket */

        $basket->apply($productHasBeenRemovedFromTheBasket);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::handleBasketHasBeenClosed
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Basket::close
     */
    public function testHandleBasketHasBeenClosed()
    {
        $id = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $id Id */

        $basket = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $id,
                'email@owner.com'
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $basket->method('findHandleMethod')
            ->willReturn('handleBasketHasBeenClosed');
        /* @var $basket Basket */

        $basketHasBeenClosed = $this->getMockBuilder(BasketHasBeenClosed::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basketHasBeenClosed BasketHasBeenClosed */

        $basket->apply($basketHasBeenClosed);
        $this->assertFalse($basket->isOpen());
        $this->assertSame($basketHasBeenClosed, $basket->getUncommittedEvents()->offsetGet(0));
    }

}
