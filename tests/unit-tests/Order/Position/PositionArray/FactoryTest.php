<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray;

use BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position as BasketPosition;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\PositionArray as BasketPositions;
use BartoszBartniczak\EventSourcing\Shop\Product\Id;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\InMemoryRepository;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Repository as ProductRepository;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\Factory::createEmpty
     */
    public function testCreateEmpty()
    {

        $productRepository = $this->getMockBuilder(ProductRepository::class)
            ->getMockForAbstractClass();
        /* @var $productRepository ProductRepository */

        $keyNamingStrategy = $this->getMockBuilder(KeyNamingStrategy::class)
            ->getMockForAbstractClass();
        /* @var $keyNamingStrategy KeyNamingStrategy */

        $factory = new Factory($productRepository, $keyNamingStrategy);
        $positions = $factory->createEmpty();

        $this->assertSame($keyNamingStrategy, $positions->getKeyNamingStrategy());
        $this->assertEquals(0, $positions->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\Factory::createFromBasketPositions
     */
    public function testCreateFromBasketPositions()
    {

        $keyNamingStrategy = new ProductIdStrategy();

        $productId1 = new Id(uniqid());

        $product1 = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getId',
                'getName'
            ])
            ->getMock();
        $product1->method('getId')
            ->willReturn($productId1);
        /* @var $product1 Product */

        $productId2 = new Id(uniqid());

        $product2 = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getId',
                'getName'
            ])
            ->getMock();
        $product2->method('getId')
            ->willReturn($productId2);
        /* @var $product2 Product */

        $productRepository = new InMemoryRepository();
        $productRepository->save($product1);
        $productRepository->save($product2);

        $basketPositions = new BasketPositions();
        $basketPositions[] = new BasketPosition($productId1, 2.0);
        $basketPositions[] = new BasketPosition($productId2, 1.5);


        $factory = new Factory($productRepository, $keyNamingStrategy);
        $positions = $factory->createFromBasketPositions($basketPositions);
        $this->assertEquals(2, $positions->count());
        $this->assertSame($product1, $positions->first()->getProduct());
        $this->assertSame(2.0, $positions->first()->getQuantity());
        $this->assertSame($product2, $positions->last()->getProduct());
        $this->assertSame(1.5, $positions->last()->getQuantity());
    }

}
