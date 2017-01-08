<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Repository;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id;
use BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository;

class InMemoryRepository implements BasketRepository
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
     * @param string $userEmail
     * @return Basket
     */
    public function findLastBasketByUserEmail(string $userEmail): Basket
    {
        $propertyName = $this->inMemoryEventRepository->getEventSerializer()->getPropertyKey('name');
        $eventStream = $this->inMemoryEventRepository->find('Basket', ['lastBasket' => $this->filterName($propertyName, $userEmail)]);

        if ($eventStream->isEmpty()) {
            throw new CannotFindBasketException(sprintf("Cannot find basket for user: '%s'.", $userEmail));
        }

        $basket = $this->factory->createEmpty();
        $basket->applyEventStream($eventStream);
        $basket->commit();

        return $this->findBasket($basket->getId());
    }

    /**
     * @param string $propertyName
     * @param string $userEmail
     * @return callable
     */
    protected function filterName(string $propertyName, string $userEmail): callable
    {
        return function ($serializedEvent) use ($propertyName, $userEmail) {

            $event = $this->inMemoryEventRepository->getEventSerializer()->deserialize($serializedEvent);
            /* @var $event \Shop\Basket\Event\Event */

            if ($event->getBasket()->getOwnerEmail() !== $userEmail) {
                return false;
            }

            if ($event->getName() !== BasketHasBeenCreated::class) {
                return false;
            }

            return true;

        };
    }

    /**
     * @param Id $basketId
     * @return Basket
     */
    public function findBasket(Id $basketId): Basket
    {
        $eventStream = $this->inMemoryEventRepository->find('Basket', ['basketId' => $this->filterId($basketId)]);

        if ($eventStream->isEmpty()) {
            throw new CannotFindBasketException(sprintf("There is no basket with id: '%s'", $basketId->toNative()));
        }

        $basket = $this->factory->createEmpty();
        $basket->applyEventStream($eventStream);
        $basket->commit();
        return $basket;
    }

    /**
     * @param Id $basketId
     * @return callable
     */
    protected function filterId(Id $basketId): callable
    {
        return function ($serializedEvent) use ($basketId) {
            $event = $this->inMemoryEventRepository->getEventSerializer()->deserialize($serializedEvent);
            /* @var $event \Shop\Basket\Event\Event */

            if ($event->getBasket()->getId()->toNative() !== $basketId->toNative()) {
                return false;
            }

            return true;
        };
    }

}