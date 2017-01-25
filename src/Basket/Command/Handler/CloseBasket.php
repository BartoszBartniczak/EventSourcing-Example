<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenClosed;

class CloseBasket extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command)
    {
        /* @var $command \BartoszBartniczak\EventSourcing\Shop\Basket\Command\CloseBasket */
        $command->getBasket()
            ->apply(
                new BasketHasBeenClosed(
                    $this->generateEventId(),
                    $this->generateDateTime(),
                    $command->getBasket()->getId()
                )
            );

        return $command->getBasket();
    }


}