<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository;


use BartoszBartniczak\EventSourcing\Shop\Product\Id;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;

class InMemoryRepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\InMemoryRepository::__construct
     */
    public function testConstructor()
    {

        $inMemoryRepository = new InMemoryRepository();
        $this->assertInstanceOf(Repository::class, $inMemoryRepository);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\InMemoryRepository::save
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\InMemoryRepository::findById
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\InMemoryRepository::findByName
     */
    public function testSavingAndSearching()
    {
        $id1 = new Id(uniqid());
        $id2 = new Id(uniqid());

        $product1 = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getId',
                'getName'
            ])
            ->getMock();
        $product1->method('getId')->willReturn($id1);
        $product1->method('getName')->willReturn('Milk');
        /* @var $product1 Product */

        $product2 = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getId',
                'getName'
            ])
            ->getMock();
        $product2->method('getId')->willReturn($id2);
        $product2->method('getName')->willReturn('Bread');
        /* @var $product2 Product */

        $inMemoryRepository = new InMemoryRepository();
        $inMemoryRepository->save($product1);
        $inMemoryRepository->save($product2);

        $this->assertSame($product1, $inMemoryRepository->findById($id1));
        $this->assertSame($product2, $inMemoryRepository->findById($id2));

        $this->assertSame($product1, $inMemoryRepository->findByName('Milk'));
        $this->assertSame($product2, $inMemoryRepository->findByName('Bread'));
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\InMemoryRepository::findById
     */
    public function testFindByIdThrowsCannotFindProductException()
    {
        $this->expectException(CannotFindProductException::class);
        $this->expectExceptionMessage("There is no product with ID 'f1a363a6-7016-458c-9208-85bf65ed13b7' in repository.");

        $id = new Id('f1a363a6-7016-458c-9208-85bf65ed13b7');

        $inMemoryRepository = new InMemoryRepository();
        $inMemoryRepository->findById($id);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\InMemoryRepository::findByName
     */
    public function testFindByNameThrowsCannotFindProductException()
    {
        $this->expectException(CannotFindProductException::class);
        $this->expectExceptionMessage("Cannot find a product with name 'Batmobile' in repository.");

        $inMemoryRepository = new InMemoryRepository();
        $inMemoryRepository->findByName('Batmobile');
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\InMemoryRepository::find
     */
    public function testFind()
    {

        $id = new Id(uniqid());

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getId',
                'getName'
            ])
            ->getMock();
        $product->method('getId')
            ->willReturn($id);
        $product->method('getName')
            ->willReturn('not important');
        /* @var $product Product */

        $inMemoryRepository = new InMemoryRepository();
        $inMemoryRepository->save($product);

        $result = $inMemoryRepository->find();
        $this->assertEquals(1, $result->count());
        $this->assertSame($product, $result->first());
    }

}
