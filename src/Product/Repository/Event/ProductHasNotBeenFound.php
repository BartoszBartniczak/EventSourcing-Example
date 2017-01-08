<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event;

use BartoszBartniczak\EventSourcing\Event\Id;

class ProductHasNotBeenFound extends Event
{
    /**
     * @var string
     */
    private $productName;

    /**
     * @var string
     */
    private $userEmail;

    /**
     * ProductHasNotBeenFoundEvent constructor.
     * @param Id $eventId
     * @param \DateTime $dateTime
     * @param string $productName
     * @param string $userEmail
     */
    public function __construct(Id $eventId, \DateTime $dateTime, string $productName, string $userEmail)
    {
        parent::__construct($eventId, $dateTime);
        $this->productName = $productName;
        $this->userEmail = $userEmail;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

}