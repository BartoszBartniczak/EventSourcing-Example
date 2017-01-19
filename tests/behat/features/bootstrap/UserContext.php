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
use BartoszBartniczak\EventSourcing\Shop\User\Command\ActivateUser as ActivateUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\ActivateUser as ActivateUserHandler;
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
     * @var InMemoryUserRepository
     */
    private $userRepository;

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
        $this->commandBus->registerHandler(ActivateUserCommand::class, new ActivateUserHandler($this->uuidGenerator));
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

    /**
     * @Given Inactive account with email: :email and token: :token
     */
    public function inactiveAccountWithEmailAndToken(string $email, string $token)
    {
        $userPassword = '';
        $senderService = new NullEmailSenderService();
        $tokenGenerator = Mockery::mock(ActivationTokenGenerator::class)
            ->shouldReceive('generate')
            ->andReturn($token)
            ->getMock();
        /* @var $tokenGenerator ActivationTokenGenerator */
        $hashGenerator = new HashGenerator();
        $emailFactory = new EmailFactory($this->uuidGenerator);
        $emailObject = $emailFactory->createEmpty();

        $registerNewUser = new RegisterNewUserCommand($email, $userPassword, $senderService, $tokenGenerator, $this->uuidGenerator, $hashGenerator, $emailObject);
        $this->commandBus->execute($registerNewUser);
    }

    /**
     * @When User is trying to activate the account with email: :email and token: :token
     */
    public function userIsTryingToActivateTheAccountWithEmailAndToken($email, $token)
    {
        $userFactory = new UserFactory();
        $userRepository = new InMemoryUserRepository($this->eventRepository, $userFactory);
        $activateUserCommand = new ActivateUserCommand($email, $token, $userRepository);
        $this->commandBus->execute($activateUserCommand);
        $this->user = $userRepository->findUserByEmail($email);
    }

    /**
     * @Then Account should be activated
     */
    public function accountShouldBeActivated()
    {
        PHPUnit_Framework_Assert::assertTrue($this->user->isActive());
    }

    /**
     * @Then Account should not be activated
     */
    public function accountShouldNotBeActivated()
    {
        PHPUnit_Framework_Assert::assertFalse($this->user->isActive());
    }

    /**
     * @Then Number of invalid attempts of activating the account should be equal: :quantity
     */
    public function numberOfInvalidAttemptsOfActivatingAccountShouldBeEqual(int $quantity)
    {
        PHPUnit_Framework_Assert::assertSame($quantity, $this->user->getUnsuccessfulAttemptsOfActivatingUserAccount());
    }

    /**
     * @Given Active account with email: :email and token: :token
     */
    public function activeAccountWithEmailAndToken(string $email, string $token)
    {
        $userPassword = '';
        $senderService = new NullEmailSenderService();
        $tokenGenerator = Mockery::mock(ActivationTokenGenerator::class)
            ->shouldReceive('generate')
            ->andReturn($token)
            ->getMock();
        /* @var $tokenGenerator ActivationTokenGenerator */
        $hashGenerator = new HashGenerator();
        $emailFactory = new EmailFactory($this->uuidGenerator);
        $emailObject = $emailFactory->createEmpty();

        $registerNewUser = new RegisterNewUserCommand($email, $userPassword, $senderService, $tokenGenerator, $this->uuidGenerator, $hashGenerator, $emailObject);
        $this->commandBus->execute($registerNewUser);

        $userFactory = new UserFactory();
        $userRepository = new InMemoryUserRepository($this->eventRepository, $userFactory);
        $activateUserCommand = new ActivateUserCommand($email, $token, $userRepository);
        $this->commandBus->execute($activateUserCommand);
        $this->user = $userRepository->findUserByEmail($email);
    }

    /**
     * @Then The attempt should be stored in system
     */
    public function theAttemptShouldBeStoredInSystem()
    {
        $event = $this->user->getCommittedEvents()->last();
        PHPUnit_Framework_Assert::assertInstanceOf(\BartoszBartniczak\EventSourcing\Shop\User\Event\AttemptOfActivatingAlreadyActivatedAccount::class, $event);
    }
}
