<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command;


use BartoszBartniczak\CQRS\Command\Query;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Repository;
use BartoszBartniczak\EventSourcing\Shop\User\User;

class FindProductByNameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\FindProductByName::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\FindProductByName::getUser
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\FindProductByName::getProductName
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\FindProductByName::getProductRepository
     */
    public function testGetters()
    {

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $user User */

        $repository = $this->getMockBuilder(Repository::class)
            ->getMockForAbstractClass();
        /* @var $repository Repository */

        $findProductByName = new FindProductByName($user, 'ProductName', $repository);
        $this->assertInstanceOf(Query::class, $findProductByName);
        $this->assertSame($user, $findProductByName->getUser());
        $this->assertSame('ProductName', $findProductByName->getProductName());
        $this->assertSame($repository, $findProductByName->getProductRepository());
    }

}
