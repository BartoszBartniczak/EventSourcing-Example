<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenClosed;
use BartoszBartniczak\EventSourcing\Shop\Command\Handler\CommandHandler;

class CloseBasket extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command)
    {
        /* @var $command \Shop\Basket\Command\CloseBasket */
        $command->getBasket()
            ->apply(
                new BasketHasBeenClosed(
                    $this->generateEventId(),
                    $this->generateDateTime(),
                    $command->getBasket()
                )
            );

        return $command->getBasket();
    }


}