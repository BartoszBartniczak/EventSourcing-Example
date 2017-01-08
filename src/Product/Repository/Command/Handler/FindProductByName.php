<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\Handler;

use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\CQRS\Command\Handler\CannotHandleTheCommandException;
use BartoszBartniczak\EventSourcing\Shop\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\CannotFindProductException;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\FindProductByName as FindProductByNameCommand;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event\ProductHasNotBeenFound;

class FindProductByName extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): Product
    {
        /* @var $command FindProductByNameCommand */

        try {
            $product = $command->getProductRepository()->findByName($command->getProductName());
            return $product;
        } catch (CannotFindProductException $cannotFindProductException) {
            $this->addAdditionalEvent(
                new ProductHasNotBeenFound(
                    $this->generateEventId(),
                    $this->generateDateTime(),
                    $command->getProductName(),
                    $command->getUser()->getEmail()
                )
            );
            throw new CannotHandleTheCommandException("Product has not been found in repository", null, $cannotFindProductException);
        }

    }

}