<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Generator;


class ActivationTokenGenerator implements Generator
{
    public function __construct()
    {
    }

    public function generate()
    {
        return uniqid();
    }

}