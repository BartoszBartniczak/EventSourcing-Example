<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Factory;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Id;
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
     * @param string $ownerEmail
     * @param string $id This parameter is optional. If empty, new Id is generated
     * @return Basket
     */
    public function createNew(string $ownerEmail, string $id = ''): Basket
    {
        if (empty($id)) {
            $id = $this->generateNewId();
        } else {
            $id = new Id($id);
        }

        return new Basket($id, $ownerEmail);
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