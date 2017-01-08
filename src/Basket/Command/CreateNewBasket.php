<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory;

class CreateNewBasket implements Command
{

    /**
     * @var Factory
     */
    private $basketFactory;

    /**
     * @var string
     */
    private $userEmail;

    /**
     * CreateNewBasket constructor.
     * @param Factory $factory
     * @param string $userEmail
     */
    public function __construct(Factory $factory, string $userEmail)
    {
        $this->userEmail = $userEmail;
        $this->basketFactory = $factory;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    /**
     * @return Factory
     */
    public function getBasketFactory(): Factory
    {
        return $this->basketFactory;
    }


}