<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Repository;


use BartoszBartniczak\EventSourcing\Event\Repository\InMemoryEventRepository;
use BartoszBartniczak\EventSourcing\Event\Serializer\Serializer;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Order\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Order\Event\OrderHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Order\Factory\Factory;
use BartoszBartniczak\EventSourcing\Shop\Order\Id;
use BartoszBartniczak\EventSourcing\Shop\Order\Order;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray;
use BartoszBartniczak\EventSourcing\UUID\Generator;

class InMemoryRepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Repository\InMemoryRepository::findById
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Repository\InMemoryRepository::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Repository\InMemoryRepository::filterId
     */
    public function testFindById()
    {

        $orderId1 = new Id('af7f62bd-3ec5-48ca-a000-260b669547b8');
        $orderId2 = new Id('6e54c395-6752-43c2-807a-31610fa73cc6');

        $basketId1 = new BasketId('51a1ebf3-2319-4dba-907a-4319db80e826');
        $basketId2 = new BasketId('60d34f2b-fc87-4047-926a-ca377b2c921d');

        $event1 = $this->createOrderHasBeenCreatedMock($orderId1, $basketId1);
        $event2 = $this->createOrderHasBeenCreatedMock($orderId2, $basketId2);

        $eventJson1 = $this->createEventJson($event1);
        $eventJson2 = $this->createEventJson($event2);

        $eventSerializer = $this->createEventSerializer([
                [$event1, $eventJson1],
                [$event2, $eventJson2]
            ]
        );

        $inMemoryEventRepository = new InMemoryEventRepository($eventSerializer);
        $inMemoryEventRepository->saveEvent($event1);
        $inMemoryEventRepository->saveEvent($event2);


        $orderMock = $this->createEmptyOrderMock();
        $factory = $this->createFactoryMock($orderMock);

        $inMemoryRepository = $this->getMockBuilder(InMemoryRepository::class)
            ->setConstructorArgs([
                $inMemoryEventRepository, $factory
            ])
            ->setMethods([
                'createEmptyOrder'
            ])
            ->getMock();
        $inMemoryRepository->method('createEmptyOrder')
            ->willReturn($orderMock);
        /* @var $inMemoryRepository InMemoryRepository */

        $order = $inMemoryRepository->findById($orderId1);
        $this->assertSame($orderMock, $order);
        $this->assertSame($orderMock->getOrderId()->toNative(), $orderId1->toNative());
    }

    /**
     * @param Id $orderId1
     * @param BasketId $basketId
     * @return OrderHasBeenCreated|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createOrderHasBeenCreatedMock(Id $orderId1, BasketId $basketId): OrderHasBeenCreated
    {
        $positionArray = $this->createEmptyPositionArrayMock();

        $event = $this->getMockBuilder(OrderHasBeenCreated::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getOrderId',
                'getBasketId',
                'getPositions',
                'getName',
            ])
            ->getMock();
        $event->method('getOrderId')
            ->willReturn($orderId1);
        $event->method('getBasketId')
            ->willReturn($basketId);
        $event->method('getPositions')
            ->willReturn($positionArray);
        $event->method('getName')
            ->willReturn(OrderHasBeenCreated::class);
        return $event;
        /* @var $event OrderHasBeenCreated */
    }

    /**
     * @return PositionArray|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createEmptyPositionArrayMock(): PositionArray
    {
        $positionArray = $this->getMockBuilder(PositionArray::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        return $positionArray;
        /* @var $positionArray PositionArray */
    }

    /**
     * @param Event $event
     * @return string
     */
    private function createEventJson(Event $event): string
    {
        if ($event instanceof OrderHasBeenCreated) {
            $eventJson = '{"event_family_name":"Order","name":"BartoszBartniczak\\EventSourcing\\Shop\\Order\\Event\\OrderHasBeenCreated", "orderId":"' . $event->getOrderId()->toNative() . '", "basketId": "' . $event->getBasketId()->toNative() . '"}';
        } else {
            $eventJson = '{"event_family_name":"Order","name":"BartoszBartniczak\\EventSourcing\\Shop\\Order\\Event\\OrderHasBeenCreated", "orderId":"' . $event->getOrderId()->toNative() . '}';
        }
        return $eventJson;
    }

    /**
     * @param array $serializeMap
     * @return Serializer|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createEventSerializer(array $serializeMap)
    {
        $eventSerializer = $this->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'serialize',
                'deserialize'
            ])
            ->getMock();


        $deserializeMap = [];

        foreach ($serializeMap as $element) {
            list($event, $json) = $element;
            $deserializeMap[] = [$json, $event];
        }

        $eventSerializer->method('serialize')
            ->willReturnMap($serializeMap);
        $eventSerializer->method('deserialize')
            ->willReturnMap($deserializeMap);
        return $eventSerializer;
        /* @var $eventSerializer Serializer */
    }

    /**
     * @return Order|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createEmptyOrderMock()
    {
        $orderMock = $this->getMockBuilder(Order::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $orderMock->method('findHandleMethod')
            ->willReturn('handleOrderHasBeenCreated');
        return $orderMock;
        /* @var $orderMock Order */
    }

    /**
     * @param Order $order
     * @return Factory
     */
    private function createFactoryMock(Order $order): Factory
    {
        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $uuidGenerator Generator */
        $factory = $this->getMockBuilder(Factory::class)
            ->setConstructorArgs([$uuidGenerator])
            ->setMethods([
                'createEmpty'
            ])
            ->getMock();
        $factory->method('createEmpty')
            ->willReturn($order);
        /* @var $factory Factory */

        return $factory;
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Repository\InMemoryRepository::findByBasketId
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Repository\InMemoryRepository::filterBasketId
     */
    public function testFindByBasketId()
    {

        $orderId1 = new Id('c4b6d3f0-6683-4ae8-bed9-3672f3a6fc4d');
        $orderId2 = new Id('b2f3c8e1-eb5d-42e3-a44f-b838aeab962c');
        $orderId3 = new Id('07b7eb31-1d04-4026-a223-6dc677414899');

        $basketId1 = new BasketId('51a1ebf3-2319-4dba-907a-4319db80e826');
        $basketId2 = new BasketId('60d34f2b-fc87-4047-926a-ca377b2c921d');


        $event1 = $this->createOrderHasBeenCreatedMock($orderId1, $basketId1);
        $event2 = $this->createOrderHasBeenCreatedMock($orderId2, $basketId2);
        $event3 = $this->createOtherOrderEvent($orderId3);

        $eventJson1 = $this->createEventJson($event1);
        $eventJson2 = $this->createEventJson($event2);
        $eventJson3 = $this->createEventJson($event3);

        $serializer = $this->createEventSerializer([
            [$event1, $eventJson1],
            [$event2, $eventJson2],
            [$event3, $eventJson3],
        ]);

        $inMemoryEventRepository = new InMemoryEventRepository($serializer);
        $inMemoryEventRepository->saveEvent($event1);
        $inMemoryEventRepository->saveEvent($event2);
        $inMemoryEventRepository->saveEvent($event3);

        $orderMock = $this->createEmptyOrderMock();
        $factory = $this->createFactoryMock($orderMock);

        $inMemoryRepository = $this->getMockBuilder(InMemoryRepository::class)
            ->setConstructorArgs([
                $inMemoryEventRepository,
                $factory
            ])
            ->setMethods([
                    'createEmptyOrder'
                ]
            )
            ->getMock();
        $inMemoryRepository->method('createEmptyOrder')
            ->willReturn($orderMock);
        /* @var $inMemoryRepository InMemoryRepository */
        $order = $inMemoryRepository->findByBasketId($basketId1);
        $this->assertSame($orderMock, $order);
        $this->assertSame($orderId1->toNative(), $order->getOrderId()->toNative());
        $this->assertSame($basketId1->toNative(), $order->getBasketId()->toNative());
    }

    /**
     * @param Id $orderId
     * @return Event|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createOtherOrderEvent(Id $orderId): Event
    {
        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getOrderId',
                'getName',
            ])
            ->getMock();
        $event->method('getOrderId')
            ->willReturn($orderId);
        $event->method('getName')
            ->willReturn(Event::class);
        /* @var $event Event */
        return $event;
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Repository\InMemoryRepository::findById
     */
    public function testFindByIdThrowsExceptionIfDataIsNotFound()
    {
        $this->expectException(CannotFindOrderException::class);
        $this->expectExceptionMessage("There is no Order with ID: 'd8b97b17-99bf-4e4f-b4ef-e0779f03cace' in repository.");

        $orderId = new Id('d8b97b17-99bf-4e4f-b4ef-e0779f03cace');

        $inMemoryEventRepository = new InMemoryEventRepository($this->createEventSerializer([]));
        $factory = $this->createFactoryMock($this->createEmptyOrderMock());

        $inMemoryRepository = new InMemoryRepository($inMemoryEventRepository, $factory);
        $inMemoryRepository->findById($orderId);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Repository\InMemoryRepository::findByBasketId
     */
    public function testFindByBasketIdThrowsExceptionIfDataIsNotFound()
    {
        $this->expectException(CannotFindOrderException::class);
        $this->expectExceptionMessage("here is no Order connected with Basket with ID: '53c6d51b-cc84-4377-9807-6c77c8932b66'.");

        $basketId = new BasketId('53c6d51b-cc84-4377-9807-6c77c8932b66');

        $inMemoryEventRepository = new InMemoryEventRepository($this->createEventSerializer([]));
        $factory = $this->createFactoryMock($this->createEmptyOrderMock());

        $inMemoryRepository = new InMemoryRepository($inMemoryEventRepository, $factory);
        $inMemoryRepository->findByBasketId($basketId);
    }


}
