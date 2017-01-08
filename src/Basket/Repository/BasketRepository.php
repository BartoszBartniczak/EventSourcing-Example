<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Repository;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id;

interface BasketRepository
{
    public function findBasket(Id $basketId): Basket;

    public function findLastBasketByUserEmail(string $userEmail): Basket;

}