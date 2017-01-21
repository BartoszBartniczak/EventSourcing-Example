<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Factory;


use BartoszBartniczak\EventSourcing\Shop\Product\Id;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;
use BartoszBartniczak\EventSourcing\UUID\Generator;

class Factory
{
    /**
     * @var Generator
     */
    private $uuidGenerator;

    /**
     * Factory constructor.
     * @param Generator $uuidGenerator
     */
    public function __construct(Generator $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @param string $name
     * @param string $productId This parameter is optional. If empty, generates new Id.
     * @return Product
     */
    public function createNew(string $name, string $productId = ''): Product
    {
        if (empty($productId)) {
            $productId = $this->generateNewId();
        } else {
            $productId = new Id($productId);
        }

        return new Product($productId, $name);
    }

    /**
     * @return Id
     */
    private function generateNewId(): Id
    {
        $uuid = $this->uuidGenerator->generate();
        return new Id($uuid->toNative());
    }

}