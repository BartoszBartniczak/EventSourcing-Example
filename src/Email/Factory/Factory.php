<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Factory;


use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Email\Id;
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


    public function createEmpty(): Email
    {

        return new Email($this->generateNewId());

    }

    private function generateNewId(): Id
    {
        return new Id($this->uuidGenerator->generate()->toNative());
    }

}