<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Repository;


use BartoszBartniczak\EventSourcing\Event\Repository\InMemoryEventRepository;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Order\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Order\Event\OrderHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Order\Factory\Factory;
use BartoszBartniczak\EventSourcing\Shop\Order\Id;
use BartoszBartniczak\EventSourcing\Shop\Order\Order;

class InMemoryRepository implements OrderRepository
{

    /**
     * @var InMemoryEventRepository
     */
    private $inMemoryEventRepository;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * InMemoryRepository constructor.
     * @param InMemoryEventRepository $inMemoryEventRepository
     * @param Factory $factory
     */
    public function __construct(InMemoryEventRepository $inMemoryEventRepository, Factory $factory)
    {
        $this->inMemoryEventRepository = $inMemoryEventRepository;
        $this->factory = $factory;
    }

    /**
     * @param BasketId $basketId
     * @return Order
     * @throws CannotFindOrderException
     */
    public function findByBasketId(BasketId $basketId): Order
    {
        $events = $this->inMemoryEventRepository->find(Event::FAMILY_NAME, ['basketId' => $this->filterBasketId($basketId)]);

        if ($events->isEmpty()) {
            throw new CannotFindOrderException(sprintf("There is no Order connected with Basket with ID: '%s'.", $basketId->toNative()));
        }

        $order = $this->factory->createEmpty();
        $order->applyEventStream($events);
        $orderId = $order->getOrderId();

        return $this->findById($orderId);
    }

    /**
     * @param BasketId $basketId
     * @return callable
     */
    private function filterBasketId(BasketId $basketId): callable
    {
        return function (Event $event) use ($basketId) {
            if ($event->getName() !== OrderHasBeenCreated::class) {
                return false;
            }
            /* @var $event OrderHasBeenCreated */
            return $event->getBasketId()->toNative() === $basketId->toNative();

        };
    }

    /**
     * @param Id $id
     * @return Order
     * @throws CannotFindOrderException
     */
    public function findById(Id $id): Order
    {
        $events = $this->inMemoryEventRepository->find(Event::FAMILY_NAME, ['id' => $this->filterId($id)]);

        if ($events->isEmpty()) {
            throw new CannotFindOrderException(sprintf("There is no Order with ID: '%s' in repository.", $id->toNative()));
        }

        $order = $this->factory->createEmpty();
        $order->applyEventStream($events);
        return $order;
    }

    /**
     * @param Id $id
     * @return callable
     */
    private function filterId(Id $id): callable
    {
        return function (Event $event) use ($id) {

            return $event->getOrderId()->toNative() === $id->toNative();

        };
    }


}