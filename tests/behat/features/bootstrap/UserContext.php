<?php

use BartoszBartniczak\CQRS\Command\Bus\CannotExecuteTheCommandException;
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
use BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\LogInUser as LoginUserHandler;
use BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\RegisterNewUser as RegisterNewUserHandler;
use BartoszBartniczak\EventSourcing\Shop\User\Command\LogInUser as LoginUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser as RegisterNewUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Event\AttemptOfLoggingInToInactiveAccount;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UnsuccessfulAttemptOfLoggingIn;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenLoggedIn;
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
     * @var UserFactory
     */
    private $userFactory;
    /**
     * @var HashGenerator
     */
    private $hashGenerator;

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
        $this->hashGenerator = new HashGenerator();

        $this->clearEventRepository();
        $this->userFactory = new UserFactory();
        $this->userRepository = new InMemoryUserRepository($this->eventRepository, $this->userFactory);
        $eventBus = new SimpleEventBus();

        $this->commandBus = new CommandBus($this->uuidGenerator, $this->eventRepository, $eventBus);
        $this->commandBus->registerHandler(RegisterNewUserCommand::class, new RegisterNewUserHandler($this->uuidGenerator));
        $this->commandBus->registerHandler(SendEmailCommand::class, new SendEmailHandler($this->uuidGenerator));
        $this->commandBus->registerHandler(ActivateUserCommand::class, new ActivateUserHandler($this->uuidGenerator));
        $this->commandBus->registerHandler(LoginUserCommand::class, new LoginUserHandler($this->uuidGenerator));
    }

    private function clearEventRepository()
    {
        $this->eventRepository = new InMemoryEventRepository(new FakeSerializer());
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
        $this->registerUser($this->userEmail, $this->userPassword);
    }

    private function registerUser(string $userEmail, string $password, ActivationTokenGenerator $activationTokenGenerator = null)
    {
        $emailService = new NullEmailSenderService();
        if (!$activationTokenGenerator instanceof ActivationTokenGenerator) {
            $activationTokenGenerator = new ActivationTokenGenerator();
        }

        $emailFactory = new EmailFactory($this->uuidGenerator);
        $this->email = $emailFactory->createEmpty();

        $command = new RegisterNewUserCommand($userEmail, $password, $emailService, $activationTokenGenerator, $this->uuidGenerator, $this->hashGenerator, $this->email);
        $this->commandBus->execute($command);

        $this->user = $this->userRepository->findUserByEmail($userEmail);
    }

    /**
     * @Then The account should be registered in system
     */
    public function theAccountShouldBeRegisteredInSystem()
    {
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
        $tokenGenerator = Mockery::mock(ActivationTokenGenerator::class)
            ->shouldReceive('generate')
            ->andReturn($token)
            ->getMock();
        /* @var $tokenGenerator ActivationTokenGenerator */

        $this->registerUser($email, $userPassword, $tokenGenerator);
    }

    /**
     * @When User is trying to activate the account with email: :email and token: :token
     */
    public function userIsTryingToActivateTheAccountWithEmailAndToken(string $email, string $token)
    {
        $this->activateAccount($email, $token);
    }

    private function activateAccount(string $email, string $activationToken)
    {
        $activateUserCommand = new ActivateUserCommand($email, $activationToken, $this->userRepository);
        $this->commandBus->execute($activateUserCommand);
        $this->user = $this->userRepository->findUserByEmail($email);
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
        $tokenGenerator = Mockery::mock(ActivationTokenGenerator::class)
            ->shouldReceive('generate')
            ->andReturn($token)
            ->getMock();
        /* @var $tokenGenerator ActivationTokenGenerator */
        $this->registerUser($email, $userPassword, $tokenGenerator);
        $this->activateAccount($email, $token);
    }

    /**
     * @Then The attempt should be stored in system
     */
    public function theAttemptShouldBeStoredInSystem()
    {
        $event = $this->user->getCommittedEvents()->last();
        PHPUnit_Framework_Assert::assertInstanceOf(\BartoszBartniczak\EventSourcing\Shop\User\Event\AttemptOfActivatingAlreadyActivatedAccount::class, $event);
    }

    /**
     * @Given Active User account with email: :email and password: :password
     */
    public function activeUserAccountWithEmailAndPassword($email, $password)
    {
        $tokenGenerator = Mockery::mock(ActivationTokenGenerator::class)
            ->shouldReceive('generate')
            ->andReturn('secret-token')
            ->getMock();
        /* @var $tokenGenerator ActivationTokenGenerator */
        $this->registerUser($email, $password, $tokenGenerator);
        $this->activateAccount($email, 'secret-token');
    }

    /**
     * @When I try to log in with parameters: :email and :password
     */
    public function iTryToLogInWithParameters(string $email, string $password)
    {
        try {
            $command = new LoginUserCommand($email, $password, $this->hashGenerator, $this->userRepository);
            $this->commandBus->execute($command);
            $this->user = $this->userRepository->findUserByEmail($email);
        } catch (CannotExecuteTheCommandException $cannotExecuteTheCommandException) {

        }
    }

    /**
     * @Then The fact of the logging in should be registered
     */
    public function theFactOfTheLoggingInShouldBeRegistered()
    {
        PHPUnit_Framework_Assert::assertInstanceOf(UserHasBeenLoggedIn::class, $this->user->getCommittedEvents()->last());
    }

    /**
     * @Then The fact of the unsuccessful attempt of logging in should be registered
     */
    public function theFactOfTheUnsuccessfulAttemptOfLoggingInShouldBeRegistered()
    {
        PHPUnit_Framework_Assert::assertInstanceOf(UnsuccessfulAttemptOfLoggingIn::class, $this->user->getCommittedEvents()->last());
    }

    /**
     * @Given Inactive User account with email: :email and password: :password
     */
    public function inactiveUserAccountWithEmailAndPassword(string $email, $password)
    {
        $this->registerUser($email, $password);
    }

    /**
     * @Then The number of unsuccessful attempts of logging in should be equals :quantity
     */
    public function theNumberOfUnsuccessfulAttemptsOfLoggingInShouldBeEquals(int $quantity)
    {
        PHPUnit_Framework_Assert::assertEquals($quantity, $this->user->getUnsuccessfulAttemptsOfLoggingIn());
    }

    /**
     * @Then The fact of the unsuccessful attempt of logging in to the inactive account should be registered
     */
    public function theFactOfTheUnsuccessfulAttemptOfLoggingInToTheInactiveAccountShouldBeRegistered()
    {
        PHPUnit_Framework_Assert::assertInstanceOf(AttemptOfLoggingInToInactiveAccount::class, $this->user->getCommittedEvents()->last());
    }

    /**
     * @Given Empty user repository
     */
    public function emptyUserRepository()
    {
        $this->clearEventRepository();
    }

    /**
     * @Then None event should be registered
     */
    public function noneEventShouldBeRegistered()
    {
        PHPUnit_Framework_Assert::assertEquals(0, $this->eventRepository->find()->count());
    }
}
