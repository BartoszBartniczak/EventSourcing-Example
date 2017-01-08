<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;

class AddProductToTheBasket implements Command
{

    /**
     * @var  Basket
     */
    private $basket;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var float
     */
    private $quantity;

    /**
     * AddProductToTheBasket constructor.
     * @param Basket $basket
     * @param Product $product
     * @param float $quantity
     */
    public function __construct(Basket $basket, Product $product, float $quantity)
    {
        $this->basket = $basket;
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return Basket
     */
    public function getBasket(): Basket
    {
        return $this->basket;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

}