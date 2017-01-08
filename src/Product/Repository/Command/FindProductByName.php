<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command;


use BartoszBartniczak\CQRS\Command\Query;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Repository as ProductRepository;
use BartoszBartniczak\EventSourcing\Shop\User\User;

class FindProductByName implements Query
{

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $productName;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * FindProductByNameCommand constructor.
     * @param User $user
     * @param string $productName
     * @param ProductRepository $productRepository
     */
    public function __construct(User $user, $productName, ProductRepository $productRepository)
    {
        $this->user = $user;
        $this->productName = $productName;
        $this->productRepository = $productRepository;
    }


    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @return ProductRepository
     */
    public function getProductRepository(): ProductRepository
    {
        return $this->productRepository;
    }


}