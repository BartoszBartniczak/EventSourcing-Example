<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Factory;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator;

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
     * @param string $ownerEmail
     * @return Basket
     */
    public function createNew(string $ownerEmail): Basket
    {
        return new Basket($this->generateNewId(), $ownerEmail);
    }

    /**
     * @return Id
     */
    private function generateNewId(): Id
    {
        return new Id($this->uuidGenerator->generate()->toNative());
    }

    /**
     * @return Basket
     */
    public function createEmpty(): Basket
    {
        return new Basket($this->generateNewId(), '');
    }

}