<?php

use BartoszBartniczak\EventSourcing\Command\Bus\CommandBus;
use BartoszBartniczak\EventSourcing\Event\Bus\SimpleEventBus;
use BartoszBartniczak\EventSourcing\Event\Repository\InMemoryEventRepository;
use BartoszBartniczak\EventSourcing\Event\Serializer\FakeSerializer;
use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\AddProductToTheBasket as AddProductToTheBasketCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\CloseBasket as CloseBasketCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\AddProductToTheBasket as AddProductToTheBasketHandler;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\CloseBasket as CloseBasketHandler;
use BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory as BasketFactory;
use BartoszBartniczak\EventSourcing\Shop\Email\Command\Handler\SendEmail as SendEmailHandler;
use BartoszBartniczak\EventSourcing\Shop\Email\Command\SendEmail as SendEmailCommand;
use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Email\Factory\Factory as EmailFactory;
use BartoszBartniczak\EventSourcing\Shop\Email\Sender\NullEmailSenderService;
use BartoszBartniczak\EventSourcing\Shop\Order\Command\CreateOrder as CreateOrderCommand;
use BartoszBartniczak\EventSourcing\Shop\Order\Command\Handler\CreateOrder as CreateOrderHandler;
use BartoszBartniczak\EventSourcing\Shop\Order\Factory\Factory as OrderFactory;
use BartoszBartniczak\EventSourcing\Shop\Order\Order;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\Factory as PositionsFactory;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\ProductIdStrategy;
use BartoszBartniczak\EventSourcing\Shop\Order\Repository\InMemoryRepository as OrderRepository;
use BartoszBartniczak\EventSourcing\Shop\Product\Factory\Factory as ProductFactory;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\InMemoryRepository as ProductRepository;
use BartoszBartniczak\EventSourcing\UUID\RamseyGeneratorAdapter as UUIDGenerator;
use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class OrderContext implements Context
{
    /**
     * @var UUIDGenerator
     */
    private $uuidGenerator;
    /**
     * @var Basket
     */
    private $basket;
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var Email
     */
    private $email;
    /**
     * @var Order
     */
    private $order;

    /**
     * @var PositionsFactory
     */
    private $positionsFactory;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->uuidGenerator = new UUIDGenerator();
        $fakeSerializer = new FakeSerializer();
        $eventBus = new SimpleEventBus();
        $eventRepository = new InMemoryEventRepository($fakeSerializer);
        $productFactory = new ProductFactory($this->uuidGenerator);


        $this->productRepository = new ProductRepository();
        $this->productRepository->save($productFactory->createNew('Name is irrelevant.', "2925d7e0-1266-44fb-b902-80bc7076896e"));
        $this->productRepository->save($productFactory->createNew('Name is irrelevant.', "f31a23e2-fc1e-456d-8eed-4063f97efb5f"));

        $this->positionsFactory = new PositionsFactory($this->productRepository, new ProductIdStrategy());

        $orderFactory = new OrderFactory($this->uuidGenerator, $this->positionsFactory);
        $this->orderRepository = new OrderRepository($eventRepository, $orderFactory);

        $this->commandBus = new CommandBus($this->uuidGenerator, $eventRepository, $eventBus);
        $this->commandBus->registerHandler(AddProductToTheBasketCommand::class, new AddProductToTheBasketHandler($this->uuidGenerator));
        $this->commandBus->registerHandler(CreateOrderCommand::class, new CreateOrderHandler($this->uuidGenerator));
        $this->commandBus->registerHandler(CloseBasketCommand::class, new CloseBasketHandler($this->uuidGenerator));
        $this->commandBus->registerHandler(SendEmailCommand::class, new SendEmailHandler($this->uuidGenerator));
    }

    /**
     * @Given There is a Basket with ID: :basketId
     */
    public function thereIsABasketWithId($basketId)
    {
        $basketFactory = new BasketFactory($this->uuidGenerator);
        $this->basket = $basketFactory->createNew('user@email.com', $basketId);
    }

    /**
     * @Given Basket has position with Product with ID: :productId in an amount :quantity piece(s)
     */
    public function basketHasPositionWithProductWithIdInAnAmountPieces(string $productId, float $quantity)
    {
        $productId = new ProductId($productId);

        $product = $this->productRepository->findById($productId);

        $command = new AddProductToTheBasketCommand($this->basket, $product, $quantity);
        $this->commandBus->execute($command);
    }

    /**
     * @When I create the order
     */
    public function iCreateTheOrder()
    {
        $senderService = new NullEmailSenderService();
        $emailFactory = new EmailFactory($this->uuidGenerator);
        $this->email = $emailFactory->createEmpty();

        $command = new CreateOrderCommand($this->uuidGenerator, $this->basket, $senderService, $this->email, $this->positionsFactory);
        $this->commandBus->execute($command);
        $this->order = $this->orderRepository->findByBasketId($this->basket->getId());
    }

    /**
     * @Then Order should have :numberOfPositions positions
     */
    public function orderShouldHavePositions(int $numberOfPositions)
    {
        PHPUnit_Framework_Assert::assertSame($numberOfPositions, $this->order->getPositions()->count());
    }

    /**
     * @Then Basket should be closed
     */
    public function basketShouldBeClosed()
    {
        PHPUnit_Framework_Assert::assertFalse($this->basket->isOpen());
    }

    /**
     * @Then Email with the order should be send
     */
    public function emailWithTheOrderShouldBeSend()
    {
        PHPUnit_Framework_Assert::assertTrue($this->email->isSent());
    }

    /**
     * @Then Order should have position with Product ID: :productId in an amount :quantity piece(s)
     */
    public function orderShouldHavePositionWithProductIdInAnAmountPieces(string $productId, float $quantity)
    {
        $position = $this->order->getPositions()->offsetGet($productId);
        /* @var $position \BartoszBartniczak\EventSourcing\Shop\Order\Position\Position */
        PHPUnit_Framework_Assert::assertSame($productId, $position->getProduct()->getId()->toNative());
        PHPUnit_Framework_Assert::assertSame($quantity, $position->getQuantity());
    }

}
