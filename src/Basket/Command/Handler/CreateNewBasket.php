<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenCreated;
use BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\EventAggregate\EventAggregate;

class CreateNewBasket extends CommandHandler
{

    /**
     * @inheritDoc
     */
    public function handle(Command $command): EventAggregate
    {
        /* @var $command \Shop\Basket\Command\CreateNewBasket */
        $basket = $command->getBasketFactory()->createNew($command->getUserEmail());

        $basket->apply(
            new BasketHasBeenCreated(
                $this->generateEventId(),
                $this->generateDateTime(),
                $basket
            )
        );

        return $basket;
    }

}