<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenAddedToTheBasket;
use BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\EventAggregate\EventAggregate;

class AddProductToTheBasket extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): EventAggregate
    {
        /* @var $command \Shop\Basket\Command\AddProductToTheBasket */

        $command->getBasket()->apply(
            new ProductHasBeenAddedToTheBasket(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getBasket(),
                $command->getProduct(),
                $command->getQuantity())
        );

        return $command->getBasket();
    }

}