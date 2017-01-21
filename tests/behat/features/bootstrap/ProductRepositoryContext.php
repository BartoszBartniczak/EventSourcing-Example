<?php

use BartoszBartniczak\EventSourcing\Command\Bus\CommandBus;
use BartoszBartniczak\EventSourcing\Event\Bus\SimpleEventBus as EventBus;
use BartoszBartniczak\EventSourcing\Event\Repository\InMemoryEventRepository as EventRepository;
use BartoszBartniczak\EventSourcing\Event\Serializer\FakeSerializer;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\FindProductByName as FindProductByNameCommand;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\Handler\FindProductByName as FindProductByNameHandler;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event\ProductHasNotBeenFound;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\InMemoryRepository as ProductRepository;
use BartoszBartniczak\EventSourcing\Shop\User\Factory\Factory as UserFactory;
use BartoszBartniczak\EventSourcing\UUID\RamseyGeneratorAdapter as UUIDGenerator;
use Behat\Behat\Context\Context;


/**
 * Defines application features from the specific context.
 */
class ProductRepositoryContext implements Context
{
    /**
     * @var \BartoszBartniczak\EventSourcing\Shop\User\User
     */
    private $user;

    /**
     * @var ProductRepository
     */
    private $repository;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var string
     */
    private $phrase;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $uuidGenerator = new UUIDGenerator();
        $this->eventRepository = new EventRepository(new FakeSerializer());
        $eventBus = new EventBus();

        $this->commandBus = new CommandBus($uuidGenerator, $this->eventRepository, $eventBus);
        $this->commandBus->registerHandler(FindProductByNameCommand::class, new FindProductByNameHandler($uuidGenerator));
    }

    /**
     * @Given User identified by email :email
     */
    public function userIdentifiedByEmail(string $email)
    {
        $userFactory = new UserFactory();
        $this->user = $userFactory->createNew($email, '');
    }

    /**
     * @Given Empty repository
     */
    public function emptyRepository()
    {
        $this->repository = new ProductRepository();
    }

    /**
     * @When User is trying to find product called :productName
     */
    public function userIsTryingToFindProductCalled(string $productName)
    {
        $this->phrase = $productName;
        $command = new FindProductByNameCommand($this->user, $this->phrase, $this->repository);
        try {
            $this->commandBus->execute($command);
        } catch (\BartoszBartniczak\CQRS\Command\Bus\CannotExecuteTheCommandException $cannotExecuteTheCommandException) {

        }
    }

    /**
     * @Then System should store information about unsuccessful searching
     */
    public function systemShouldStoreInformationAboutUnsuccessfulSearching()
    {
        $events = $this->eventRepository->find();
        $productHasNotBeenFound = $events->offsetGet(0);
        PHPUnit_Framework_Assert::assertInstanceOf(ProductHasNotBeenFound::class, $productHasNotBeenFound);
        /* @var $productHasNotBeenFound ProductHasNotBeenFound */
        PHPUnit_Framework_Assert::assertSame($this->phrase, $productHasNotBeenFound->getProductName());
    }
}
