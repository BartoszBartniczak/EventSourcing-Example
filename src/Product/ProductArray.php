<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product;


use BartoszBartniczak\ArrayObject\ArrayOfObjects;

class ProductArray extends ArrayOfObjects
{

    public function __construct($input = null)
    {
        parent::__construct(Product::class, $input);
    }

}