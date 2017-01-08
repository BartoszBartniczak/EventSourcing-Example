<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;

class RemoveProductFromTheBasket implements Command
{

    /**
     * @var Basket
     */
    private $basket;

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * RemoveProductFromTheBasket constructor.
     * @param Basket $basket
     * @param ProductId $productId
     */
    public function __construct(Basket $basket, ProductId $productId)
    {
        $this->basket = $basket;
        $this->productId = $productId;
    }

    /**
     * @return Basket
     */
    public function getBasket(): Basket
    {
        return $this->basket;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

}