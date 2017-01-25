<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Repository;


use BartoszBartniczak\EventSourcing\Event\Repository\InMemoryEventRepository;
use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id;

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
        $eventStream = $this->inMemoryEventRepository->find('Basket', ['lastBasket' => $this->filterName($userEmail)]);

        if ($eventStream->isEmpty()) {
            throw new CannotFindBasketException(sprintf("Cannot find basket for user: '%s'.", $userEmail));
        }

        $basket = $this->factory->createEmpty();
        $basket->applyEventStream($eventStream);
        $basket->commit();

        return $this->findBasket($basket->getId());
    }

    /**
     * @param string $userEmail
     * @return callable
     */
    protected function filterName(string $userEmail): callable
    {
        return function (Event $event) use ($userEmail) {


            if ($event->getName() !== BasketHasBeenCreated::class) {
                return false;
            }

            /* @var $event BasketHasBeenCreated */
            if ($event->getOwnerEmail() !== $userEmail) {
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
        return function (Event $event) use ($basketId) {
            if ($event->getBasketId()->toNative() !== $basketId->toNative()) {
                return false;
            }

            return true;
        };
    }

}