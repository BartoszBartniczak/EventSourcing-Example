<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository;


use BartoszBartniczak\EventSourcing\Shop\Product\Product;
use BartoszBartniczak\EventSourcing\Shop\Product\ProductArray;
use BartoszBartniczak\EventSourcing\UUID\UUID;

class InMemoryRepository implements Repository
{

    /**
     * @var array
     */
    private $productsStoredById;

    /**
     * @var array
     */
    private $productStoredByName;

    /**
     * InMemoryRepository constructor.
     */
    public function __construct()
    {
        $this->productsStoredById = [];
        $this->productStoredByName = [];
    }


    /**
     * @inheritdoc
     */
    public function findById(UUID $productId): Product
    {
        if (!isset($this->productsStoredById[$productId->toNative()])) {
            throw new CannotFindProductException(sprintf("There is no product with ID '%s' in repository.", $productId->toNative()));
        }

        return $this->productsStoredById[$productId->toNative()];
    }

    /**
     * @inheritdoc
     */
    public function findByName(string $name): Product
    {
        if (!isset($this->productStoredByName[$name])) {
            throw new CannotFindProductException(sprintf("Cannot find a product with name '%s' in repository.", $name));
        }

        return $this->productStoredByName[$name];
    }


    /**
     * @inheritdoc
     */
    public function save(Product $product)
    {
        $this->productsStoredById[$product->getId()->toNative()] = $product;
        $this->productStoredByName[$product->getName()] = $product;
    }

    /**
     * @inheritdoc
     */
    public function find(): ProductArray
    {
        return new ProductArray($this->productsStoredById);
    }


}