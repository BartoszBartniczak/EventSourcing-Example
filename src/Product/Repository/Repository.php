<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository;


use BartoszBartniczak\EventSourcing\Shop\Product\Product;
use BartoszBartniczak\EventSourcing\UUID\UUID;

interface Repository
{

    /**
     * @param UUID $productId
     * @return Product
     * @throws CannotFindProductException
     */
    public function findById(UUID $productId): Product;

    /**
     * @param string $name
     * @return Product
     * @throws CannotFindProductException
     */
    public function findByName(string $name): Product;

    /**
     * @param Product $product
     * @return void
     */
    public function save(Product $product);

}