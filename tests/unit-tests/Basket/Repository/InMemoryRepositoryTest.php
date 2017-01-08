<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Repository;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenAddedToTheBasket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Event\EventStream;
use BartoszBartniczak\EventSourcing\Event\Repository\InMemoryEventRepository;
use BartoszBartniczak\EventSourcing\Event\Serializer\Serializer;
use BartoszBartniczak\EventSourcing\UUID\Generator;

class InMemoryRepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Repository\InMemoryRepository::__construct
     */
    public function testConstructor()
    {

        $inMemoryEventRepository = $this->getMockBuilder(InMemoryEventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $inMemoryEventRepository InMemoryEventRepository */

        $factory = $this->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $factory Factory */

        $inMemoryRepository = new InMemoryRepository($inMemoryEventRepository, $factory);
        $this->assertInstanceOf(BasketRepository::class, $inMemoryRepository);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Repository\InMemoryRepository::findBasket
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Repository\InMemoryRepository::filterId
     */
    public function testFindBasket()
    {
        $generator = $this->createGenerator();

        $basketId1 = new BasketId('69e30282-3ff5-4b3c-889e-4cd4a9e9ef50');
        $event1 = $this->createEventMock($basketId1, 'empty');

        $basketId2 = new BasketId('0700a876-8e86-4d67-bb7c-2ba1130dc817');
        $event2 = $this->createEventMock($basketId2, 'empty');

        $map1 = $this->createSerializedEvent($basketId1, 'empty');
        $map2 = $this->createSerializedEvent($basketId2, 'empty');


        $serializer = $this->createSerializer($generator, [[$event1, $map1], [$event2, $map2]]);

        $inMemoryEventRepository = $this->createInMemoryEventRepository($serializer);
        $inMemoryEventRepository->saveEvent($event1);
        $inMemoryEventRepository->saveEvent($event2);

        $basketMock = $this->createBasketMock($basketId1, 'empty');

        $factory = $this->createBasketFactory($generator, $basketMock);

        $inMemoryRepository = $this->getMockBuilder(InMemoryRepository::class)
            ->setConstructorArgs([
                $inMemoryEventRepository, $factory
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $inMemoryRepository InMemoryRepository */


        $basket = $inMemoryRepository->findBasket($basketId1);

        $this->assertInstanceOf(Basket::class, $basket);
        $this->assertSame($basketMock, $basket);
        $this->assertEquals(0, $basket->getUncommittedEvents()->count());
        $this->assertEquals(1, $basket->getCommittedEvents()->count());
    }

    /**
     * @return Generator
     */
    private function createGenerator(): Generator
    {
        $generator = $this->getMockBuilder(Generator::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'generate'
            ])
            ->getMock();
        /* @var $generator Generator */
        return $generator;
    }

    /**
     * @param BasketId $basketId
     * @param string $ownerEmail
     * @param string $className
     * @return Event
     */
    private function createEventMock(BasketId $basketId, string $ownerEmail, $className = BasketHasBeenCreated::class): Event
    {

        $basket = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $basketId, $ownerEmail
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $basket Basket */

        $event = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->setMethods([
                'getBasket',
                'getName'
            ])
            ->getMock();
        $event->method('getBasket')
            ->willReturn($basket);
        $event->method('getName')
            ->willReturn($className);
        /* @var $event Event */

        return $event;
    }

    /**
     * @param BasketId $basketId
     * @param string $ownerEmail
     * @param string $className
     * @return string
     */
    private function createSerializedEvent(BasketId $basketId, string $ownerEmail, $className = BasketHasBeenCreated::class): string
    {
        $serializedEvent = stripslashes('{"event_family_name":"Basket","name":"' . $className . '","event_id":{"uuid":"79303a9b-7321-44e5-9746-62c1fc5d9784"},"date_time":"2016-12-27T12:18:54+0100","basket":{"id":{"uuid":"' . $basketId->toNative() . '"},"owner_email":"' . $ownerEmail . '"}}');
        return $serializedEvent;
    }

    /**
     * @param $generator
     * @param array $returnMap
     * @return Serializer
     */
    private function createSerializer($generator, array $returnMap): Serializer
    {
        $serializer = $this->getMockBuilder(Serializer::class)
            ->setConstructorArgs([
                $generator
            ])
            ->setMethods([
                'serialize',
                'deserialize',
                'getPropertyKey'
            ])
            ->getMock();
        $serializer->method('getPropertyKey')
            ->willReturnMap([
                ['eventFamilyName', 'event_family_name'],
                ['name', 'name']
            ]);
        $serializer->method('serialize')
            ->willReturnMap($returnMap);
        $serializer->method('deserialize')
            ->willReturnMap($this->getDeserializeReturnMap($returnMap));
        /* @var $serializer Serializer */
        return $serializer;
    }

    private function getDeserializeReturnMap(array $serializeReturnMap): array
    {
        $deserializeMap = [];
        foreach ($serializeReturnMap as $map) {
            $deserializeMap[] = [$map[1], $map[0]];
        }
        return $deserializeMap;
    }

    /**
     * @param $serializer
     * @return InMemoryEventRepository
     */
    private function createInMemoryEventRepository($serializer): InMemoryEventRepository
    {
        $inMemoryEventRepository = $this->getMockBuilder(InMemoryEventRepository::class)
            ->setConstructorArgs([
                $serializer
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $inMemoryEventRepository InMemoryEventRepository */
        return $inMemoryEventRepository;
    }

    /**
     * @param BasketId $basketId
     * @param string $basketOwner
     * @return Basket
     */
    private function createBasketMock(BasketId $basketId, string $basketOwner): Basket
    {
        $basket = $this->getMockBuilder(Basket::class)
            ->setConstructorArgs([
                $basketId, $basketOwner
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $basket->method('findHandleMethod')
            ->willReturn('handleBasketHasBeenCreated');
        /* @var $basket Basket */
        return $basket;
    }

    /**
     * @param Generator $generator
     * @param Basket $basket
     * @return Factory
     */
    private function createBasketFactory(Generator $generator, Basket $basket): Factory
    {
        $factory = $this->getMockBuilder(Factory::class)
            ->setConstructorArgs([
                $generator
            ])
            ->setMethods([
                'createEmpty'
            ])
            ->getMock();
        $factory->method('createEmpty')
            ->willReturn($basket);
        /* @var $factory Factory */
        return $factory;
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Repository\InMemoryRepository::findLastBasketByUserEmail
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Repository\InMemoryRepository::filterName
     */
    public function testFindLastBasketByUserEmail()
    {

        $generator = $this->createGenerator();

        $basketId1 = new BasketId('69e30282-3ff5-4b3c-889e-4cd4a9e9ef50');
        $event1 = $this->createEventMock($basketId1, 'user@user.com');
        $map1 = $this->createSerializedEvent($basketId1, 'user@user.com');

        $basketId2 = new BasketId('0700a876-8e86-4d67-bb7c-2ba1130dc817');
        $event2 = $this->createEventMock($basketId2, 'another@user.net');
        $map2 = $this->createSerializedEvent($basketId2, 'another@user.net');

        $basketId3 = new BasketId('1cf54a3c-9255-4c60-9a8c-455b8af091da');
        $event3 = $this->createEventMock($basketId3, 'user@user.com', ProductHasBeenAddedToTheBasket::class);
        $map3 = $this->createSerializedEvent($basketId3, 'user@user.com', ProductHasBeenAddedToTheBasket::class);

        $returnMap = [
            [$event1, $map1],
            [$event2, $map2],
            [$event3, $map3]
        ];
        $serializer = $this->createSerializer($generator, $returnMap);
        $inMemoryEventRepository = $this->createInMemoryEventRepository($serializer);

        $basketMock = $this->createBasketMock($basketId1, 'user@user.com');
        $factory = $this->createBasketFactory($generator, $basketMock);

        $inMemoryRepository = $this->getMockBuilder(InMemoryRepository::class)
            ->setConstructorArgs([
                $inMemoryEventRepository, $factory
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $inMemoryRepository InMemoryRepository */
        $inMemoryEventRepository->saveEvent($event1);
        $inMemoryEventRepository->saveEvent($event2);
        $inMemoryEventRepository->saveEvent($event3);

        $basket = $inMemoryRepository->findLastBasketByUserEmail('user@user.com');
        $this->assertSame($basketMock, $basket);
        $this->assertEquals(0, $basket->getUncommittedEvents()->count());
        $this->assertEquals(1, $basket->getCommittedEvents()->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Repository\InMemoryRepository::findBasket
     */
    public function testFindBasketThrowsCannotFindBasketException()
    {

        $this->expectException(CannotFindBasketException::class);
        $this->expectExceptionMessage("There is no basket with id: '1cf54a3c-9255-4c60-9a8c-455b8af091da'");

        $serializer = $this->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'serialize',
                'deserialize',
                'getPropertyKey'
            ])
            ->getMock();
        /* @var $serializer Serializer */

        $inMemoryEventRepository = $this->getMockBuilder(InMemoryEventRepository::class)
            ->setConstructorArgs([
                $serializer
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $inMemoryEventRepository InMemoryEventRepository */

        $factory = $this->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $factory Factory */

        $inMemoryRepository = $this->getMockBuilder(InMemoryRepository::class)
            ->setConstructorArgs([
                $inMemoryEventRepository,
                $factory
            ])
            ->setMethods([
                'find'
            ])
            ->getMock();
        $inMemoryRepository->method('find')
            ->willReturn(new EventStream());
        /* @var $inMemoryRepository InMemoryRepository */
        $inMemoryRepository->findBasket(new BasketId('1cf54a3c-9255-4c60-9a8c-455b8af091da'));

    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Repository\InMemoryRepository::findLastBasketByUserEmail
     */
    public function testFindLastBasketByUserEmailThrowsCannotFindBasketException()
    {
        $this->expectException(CannotFindBasketException::class);
        $this->expectExceptionMessage("Cannot find basket for user: 'user@email.com'.");

        $serializer = $this->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'serialize',
                'deserialize',
                'getPropertyKey'
            ])
            ->getMock();
        /* @var $serializer Serializer */

        $inMemoryEventRepository = $this->getMockBuilder(InMemoryEventRepository::class)
            ->setConstructorArgs([
                $serializer
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $inMemoryEventRepository InMemoryEventRepository */

        $factory = $this->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $factory Factory */

        $inMemoryRepository = $this->getMockBuilder(InMemoryRepository::class)
            ->setConstructorArgs([
                $inMemoryEventRepository,
                $factory
            ])
            ->setMethods([
                'find'
            ])
            ->getMock();
        $inMemoryRepository->method('find')
            ->willReturn(new EventStream());
        /* @var $inMemoryRepository InMemoryRepository */
        $inMemoryRepository->findLastBasketByUserEmail('user@email.com');
    }

}
