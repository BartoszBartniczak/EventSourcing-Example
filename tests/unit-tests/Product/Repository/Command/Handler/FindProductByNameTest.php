<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\Handler;

use BartoszBartniczak\CQRS\Command\Handler\CannotHandleTheCommandException;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\CannotFindProductException;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\FindProductByName as FindProductByNameCommand;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event\ProductHasNotBeenFound;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Repository;
use BartoszBartniczak\EventSourcing\Shop\User\User;
use BartoszBartniczak\EventSourcing\UUID\Generator;

class FindProductByNameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\Handler\FindProductByName::handle
     */
    public function testHandleFindsProduct()
    {

        $generator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $generator Generator */

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $user User */

        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $productMock Product */

        $productRepository = $this->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'findByName'
            ])
            ->getMockForAbstractClass();
        $productRepository->method('findByName')
            ->with('productName')
            ->willReturn($productMock);
        /* @var $productRepository Repository */

        $findProductByNameCommand = new FindProductByNameCommand($user, 'productName', $productRepository);

        $findProductByName = new FindProductByName($generator);
        $productMock = $findProductByName->handle($findProductByNameCommand);
        $this->assertInstanceOf(Product::class, $productMock);
        $this->assertSame($productMock, $productMock);
        $this->assertEquals(0, $findProductByName->getAdditionalEvents()->count());
        $this->assertEquals(0, $findProductByName->getNextCommands()->count());

    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\Handler\FindProductByName::handle
     */
    public function testHandleCreatesAdditionalEventInCaseOfNotFindingTheProduct()
    {

        $this->expectException(CannotHandleTheCommandException::class);
        $this->expectExceptionMessage("Product has not been found in repository");

        $generator = $this->getMockBuilder(Generator::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $generator Generator */

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getEmail'
            ])
            ->getMock();
        $user->method('getEmail')
            ->willReturn('user@email.com');
        /* @var $user User */

        $repository = $this->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'findByName'
            ])
            ->getMockForAbstractClass();
        $repository->expects($this->once())
            ->method('findByName')
            ->with('ProductName')
            ->willThrowException(new CannotFindProductException());
        /* @var $repository Repository */

        $findProductByNameCommand = new FindProductByNameCommand($user, 'ProductName', $repository);

        $findProductByName = new FindProductByName($generator);
        $findProductByName->handle($findProductByNameCommand);

        $this->assertEquals(1, $findProductByName->getAdditionalEvents()->count());
        $this->assertInstanceOf(ProductHasNotBeenFound::class, $findProductByName->getAdditionalEvents()->shift());
    }

}
