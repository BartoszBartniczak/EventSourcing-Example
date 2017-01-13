<?php

use BartoszBartniczak\EventSourcing\Command\Bus\CommandBus;
use BartoszBartniczak\EventSourcing\Event\Bus\SimpleEventBus;
use BartoszBartniczak\EventSourcing\Event\Repository\InMemoryEventRepository;
use BartoszBartniczak\EventSourcing\Event\Serializer\FakeSerializer;
use BartoszBartniczak\EventSourcing\Shop\Email\Command\Handler\SendEmail as SendEmailHandler;
use BartoszBartniczak\EventSourcing\Shop\Email\Command\SendEmail as SendEmailCommand;
use BartoszBartniczak\EventSourcing\Shop\Email\Email;
use BartoszBartniczak\EventSourcing\Shop\Email\Factory\Factory as EmailFactory;
use BartoszBartniczak\EventSourcing\Shop\Email\Sender\NullEmailSenderService;
use BartoszBartniczak\EventSourcing\Shop\Generator\ActivationTokenGenerator;
use BartoszBartniczak\EventSourcing\Shop\Password\HashGenerator;
use BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\RegisterNewUser as RegisterNewUserHandler;
use BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser as RegisterNewUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Factory\Factory as UserFactory;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\InMemoryUserRepository;
use BartoszBartniczak\EventSourcing\Shop\User\User;
use BartoszBartniczak\EventSourcing\UUID\RamseyGeneratorAdapter as UUIDGenerator;
use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class UserContext implements Context
{
    /**
     * @var string
     */
    private $userEmail;
    /**
     * @var string
     */
    private $userPassword;
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var UUIDGenerator
     */
    private $uuidGenerator;
    /**
     * @var Email
     */
    private $email;
    /**
     * @var InMemoryEventRepository
     */
    private $eventRepository;
    /**
     * @var User
     */
    private $user;

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

        $this->eventRepository = new InMemoryEventRepository(new FakeSerializer());
        $eventBus = new SimpleEventBus();

        $this->commandBus = new CommandBus($this->uuidGenerator, $this->eventRepository, $eventBus);
        $this->commandBus->registerHandler(RegisterNewUserCommand::class, new RegisterNewUserHandler($this->uuidGenerator));
        $this->commandBus->registerHandler(SendEmailCommand::class, new SendEmailHandler($this->uuidGenerator));
    }

    /**
     * @Given User email: :userEmail
     */
    public function userEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    /**
     * @Given User password :password
     */
    public function userPassword($password)
    {
        $this->userPassword = $password;
    }

    /**
     * @When I register in service
     */
    public function iRegisterInService()
    {
        $senderService = new NullEmailSenderService();
        $tokenGenerator = new ActivationTokenGenerator();
        $hashGenerator = new HashGenerator();
        $emailFactory = new EmailFactory($this->uuidGenerator);
        $this->email = $emailFactory->createEmpty();

        $command = new RegisterNewUserCommand($this->userEmail, $this->userPassword, $senderService, $tokenGenerator, $this->uuidGenerator, $hashGenerator, $this->email);
        $this->commandBus->execute($command);
    }

    /**
     * @Then The account should be registered in system
     */
    public function theAccountShouldBeRegisteredInSystem()
    {
        $userFactory = new UserFactory();
        $inMemoryUserRepository = new InMemoryUserRepository($this->eventRepository, $userFactory);
        $this->user = $inMemoryUserRepository->findUserByEmail($this->userEmail);
        PHPUnit_Framework_Assert::assertInstanceOf(User::class, $this->user);
        PHPUnit_Framework_Assert::assertSame($this->userEmail, $this->user->getEmail());
        PHPUnit_Framework_Assert::assertNotEmpty($this->user->getPasswordHash());
    }

    /**
     * @Then The account should be inactive
     */
    public function theAccountShouldBeInactive()
    {
        PHPUnit_Framework_Assert::assertFalse($this->user->isActive());
    }

    /**
     * @Then Registration token should be generated
     */
    public function registrationTokenShouldBeGenerated()
    {
        PHPUnit_Framework_Assert::assertNotEmpty($this->user->getActivationToken());
    }

    /**
     * @Then Email with the activation token should be sent
     */
    public function emailWithTheActivationTokenShouldBeSent()
    {
        PHPUnit_Framework_Assert::assertTrue($this->email->isSent());
    }
}
