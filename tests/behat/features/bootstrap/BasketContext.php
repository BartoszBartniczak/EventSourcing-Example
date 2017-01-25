<?php

use BartoszBartniczak\EventSourcing\Command\Bus\CommandBus;
use BartoszBartniczak\EventSourcing\Event\Bus\SimpleEventBus;
use BartoszBartniczak\EventSourcing\Event\Repository\InMemoryEventRepository;
use BartoszBartniczak\EventSourcing\Event\Serializer\FakeSerializer;
use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\AddProductToTheBasket as AddProductToTheBasketCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\ChangeQuantityOfTheProduct as ChangeQuantityOfTheProductCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\AddProductToTheBasket as AddProductToTheBasketHandler;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\ChangeQuantityOfTheProduct as ChangeQuantityOfTheProductHandler;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\RemoveProductFromTheBasket as RemoveProductFromTheBasketHandler;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\RemoveProductFromTheBasket as RemoveProductFromTheBasketCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory as BasketFactory;
use BartoszBartniczak\EventSourcing\Shop\Product\Factory\Factory as ProductFactory;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\InMemoryRepository as ProductRepository;
use BartoszBartniczak\EventSourcing\UUID\RamseyGeneratorAdapter;
use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class BasketContext implements Context
{
    /**
     * @var Basket
     */
    private $basket;
    /**
     * @var RamseyGeneratorAdapter
     */
    private $uuidGenerator;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->uuidGenerator = new RamseyGeneratorAdapter();
        $this->productRepository = new ProductRepository();

        $eventBus = new SimpleEventBus();
        $eventRepository = new InMemoryEventRepository(new FakeSerializer());

        $this->commandBus = new CommandBus($this->uuidGenerator, $eventRepository, $eventBus);
        $this->commandBus->registerHandler(AddProductToTheBasketCommand::class, new AddProductToTheBasketHandler($this->uuidGenerator));
        $this->commandBus->registerHandler(ChangeQuantityOfTheProductCommand::class, $handler = new ChangeQuantityOfTheProductHandler($this->uuidGenerator));
        $this->commandBus->registerHandler(RemoveProductFromTheBasketCommand::class, new RemoveProductFromTheBasketHandler($this->uuidGenerator));

    }

    /**
     * @Given Product with ID: :id
     */
    public function productWithId($id)
    {
        $productFactory = new ProductFactory($this->uuidGenerator);
        $this->productRepository->save($productFactory->createNew("name is irrelevant", $id));
    }

    /**
     * @Then I should have :arg1 position(s) in the Basket
     */
    public function iShouldHavePositionInTheBasket($arg1)
    {
        PHPUnit_Framework_Assert::assertEquals($arg1, $this->basket->getPositions()->count());
    }

    /**
     * @Then product with ID: :productId and in amount of :quantity
     */
    public function productWithIdAndInAmountOf($productId, $quantity)
    {
        $quantity = (float)$quantity;
        $position = $this->basket->getPositions()->offsetGet($productId);
        /* @var $position \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position */

        PHPUnit_Framework_Assert::assertSame($productId, $position->getProductId()->toNative());
        PHPUnit_Framework_Assert::assertSame($quantity, $position->getQuantity());
    }

    /**
     * @Given Basket with Product with ID: :productId in an amount of :quantity piece(s)
     */
    public function basketWithProductWithIdInAnAmountOfPieces($productId, $quantity)
    {
        $this->emptyBasket();
        $this->iAddToTheBasketProductWithIdInAnAmountOfPiece($productId, $quantity);
    }

    /**
     * @Given Empty basket
     */
    public function emptyBasket()
    {
        $basketFactory = new BasketFactory($this->uuidGenerator);

        $this->basket = $basketFactory->createEmpty();
    }

    /**
     * @When I add to the basket, product with ID: :productId in an amount of :quantity piece(s)
     */
    public function iAddToTheBasketProductWithIdInAnAmountOfPiece($productId, $quantity)
    {
        $productId = new ProductId($productId);
        $product = $this->productRepository->findById($productId);
        $command = new AddProductToTheBasketCommand($this->basket, $product, (float)$quantity);
        $this->commandBus->execute($command);
    }

    /**
     * @When I change quantity of the Product with ID: :productId to :quantity
     */
    public function iChangeQuantityOfTheProductWithIdTo($productId, $quantity)
    {
        $productId = new ProductId($productId);
        $quantity = (float)$quantity;

        $command = new ChangeQuantityOfTheProductCommand($this->basket, $productId, $quantity);
        $this->commandBus->execute($command);
    }

    /**
     * @When I remove position with product with ID: :productId
     */
    public function iRemovePositionWithProductWithId($productId)
    {
        $productId = new ProductId($productId);

        $command = new RemoveProductFromTheBasketCommand($this->basket, $productId);
        $this->commandBus->execute($command);
    }
}
