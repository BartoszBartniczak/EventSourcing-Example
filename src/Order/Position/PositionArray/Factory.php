<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray;


use BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position as BasketPosition;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\PositionArray as BasketPositions;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\Position;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Repository;

class Factory
{

    /**
     * @var Repository
     */
    protected $productRepository;

    /**
     * @var KeyNamingStrategy
     */
    protected $keyNamingStrategy;

    /**
     * Factory constructor.
     * @param Repository $productRepository
     */
    public function __construct(Repository $productRepository, KeyNamingStrategy $keyNamingStrategy)
    {
        $this->productRepository = $productRepository;
        $this->keyNamingStrategy = $keyNamingStrategy;
    }

    /**
     * @param BasketPositions $basketPositions
     * @return PositionArray
     */
    public function createFromBasketPositions(BasketPositions $basketPositions): PositionArray
    {
        $positions = new PositionArray($this->keyNamingStrategy);
        foreach ($basketPositions as $basketPosition) {
            /* @var $basketPosition BasketPosition */
            $product = $this->productRepository->findById($basketPosition->getProductId());
            $position = new Position($product, $basketPosition->getQuantity());
            $positions->append($position);
        }
        return $positions;
    }

    public function createEmpty(): PositionArray
    {
        return new PositionArray($this->keyNamingStrategy);
    }


}