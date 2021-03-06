<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket;


use BartoszBartniczak\EventSourcing\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenClosed;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenAddedToTheBasket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenRemovedFromTheBasket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\QuantityOfTheProductHasBeenChanged;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position;
use BartoszBartniczak\EventSourcing\Shop\Basket\Position\PositionArray;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;

class Basket extends EventAggregate
{

    /**
     * @var Id
     */
    private $id;

    /**
     * @var array
     */
    private $positions;

    /**
     * @var string
     */
    private $ownerEmail;

    /**
     * @var bool
     */
    private $open;

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->open;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOwnerEmail(): string
    {
        return $this->ownerEmail;
    }

    /**
     * @param BasketHasBeenCreated $basketHasBeenCreated
     */
    protected function handleBasketHasBeenCreated(BasketHasBeenCreated $basketHasBeenCreated)
    {
        $this->__construct($basketHasBeenCreated->getBasketId(), $basketHasBeenCreated->getOwnerEmail());
    }

    /**
     * Basket constructor.
     * @param Id $id
     * @param string $ownerEmail
     */
    public function __construct(Id $id, string $ownerEmail)
    {
        parent::__construct();
        $this->id = $id;
        $this->positions = new PositionArray();
        $this->ownerEmail = $ownerEmail;
        $this->open = true;
    }

    /**
     * @param ProductHasBeenAddedToTheBasket $event
     */
    protected function handleProductHasBeenAddedToTheBasket(ProductHasBeenAddedToTheBasket $event)
    {
        $this->add($event->getProductId(), $event->getQuantity());
    }

    /**
     * @param ProductId $productId
     * @param float $quantity
     */
    private function add(ProductId $productId, float $quantity)
    {
        try {
            $basketPosition = $this->findPositionByProductId($productId);
            $basketPosition->addToQuantity($quantity);
        } catch (CannotFindPositionException $invalidArgumentException) {
            $this->createNewItem($productId, $quantity);
        }
    }

    /**
     * @param ProductId $productId
     * @return Position
     * @throws CannotFindPositionException
     */
    public function findPositionByProductId(ProductId $productId): Position
    {
        if (isset($this->positions[$productId->toNative()])) {
            return $this->positions[$productId->toNative()];
        } else {
            throw new CannotFindPositionException(sprintf("Cannot find position with product id: '%s'", $productId->toNative()));
        }
    }

    /**
     * @param ProductId $productId
     * @param float $quantity
     */
    private function createNewItem(ProductId $productId, float $quantity)
    {
        $this->positions[$productId->toNative()] = new Position($productId, $quantity);
    }

    /**
     * @param QuantityOfTheProductHasBeenChanged $event
     */
    protected function handleQuantityOfTheProductHasBeenChanged(QuantityOfTheProductHasBeenChanged $event)
    {
        $this->changeQuantity($event->getProductId(), $event->getQuantity());
    }

    /**
     * @param ProductId $productId
     * @param float $quantity
     * @throws CannotFindPositionException
     */
    private function changeQuantity(ProductId $productId, float $quantity)
    {
        $basketPosition = $this->findPositionByProductId($productId);
        if ($quantity > 0) {
            $basketPosition->changeQuantity($quantity);
        } else {
            $this->remove($productId);
        }
    }

    /**
     * @param ProductId $productId
     * @throws CannotFindPositionException
     */
    private function remove(ProductId $productId)
    {
        $this->findPositionByProductId($productId);
        $this->getPositions()->offsetUnset($productId->toNative());

    }

    /**
     * @return PositionArray
     */
    public function getPositions(): PositionArray
    {
        return $this->positions;
    }

    /**
     * @param ProductHasBeenRemovedFromTheBasket $event
     */
    protected function handleProductHasBeenRemovedFromTheBasket(ProductHasBeenRemovedFromTheBasket $event)
    {
        $this->remove($event->getProductId());
    }

    protected function handleBasketHasBeenClosed(BasketHasBeenClosed $basketHasBeenClosed)
    {
        $this->close();
    }

    /**
     * @return void
     */
    private function close()
    {
        $this->open = false;
    }

}