<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Repository;

use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Order\Id;
use BartoszBartniczak\EventSourcing\Shop\Order\Order;


interface OrderRepository
{

    /**
     * @param Id $id
     * @return Order
     * @throws CannotFindOrderException
     */
    public function findById(Id $id): Order;

    /**
     * @param BasketId $basketId
     * @return Order
     * @throws CannotFindOrderException
     */
    public function findByBasketId(BasketId $basketId): Order;

}