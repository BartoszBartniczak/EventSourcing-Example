<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

require_once('vendor/autoload.php');

use BartoszBartniczak\CQRS\Command\Bus\CannotExecuteTheCommandException;
use BartoszBartniczak\EventSourcing\Command\Bus\CommandBus;
use BartoszBartniczak\EventSourcing\Event\Bus\SimpleEventBus;
use BartoszBartniczak\EventSourcing\Event\Repository\InMemoryEventRepository;
use BartoszBartniczak\EventSourcing\Event\Serializer\JMSJsonSerializer;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\AddProductToTheBasket as AddProductToTheBasketCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\ChangeQuantityOfTheProduct;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\CloseBasket as CloseBasketCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\CreateNewBasket as CreateNewBasketCommand;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\AddProductToTheBasket as AddProductToTheBasketHandler;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\ChangeQuantityOfTheProduct as ChangeQuantityOfTheProductHandler;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\CloseBasket as CloseBasketCommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\CreateNewBasket as CreateNewBasketHandler;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler\RemoveProductFromTheBasket as RemoveProductFromTheBasketHandler;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\RemoveProductFromTheBasket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory as BasketFactory;
use BartoszBartniczak\EventSourcing\Shop\Basket\Repository\InMemoryRepository as BasketRepository;
use BartoszBartniczak\EventSourcing\Shop\Email\Command\Handler\SendEmail as SendEmailCommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Email\Command\SendEmail as SendEmailCommand;
use BartoszBartniczak\EventSourcing\Shop\Email\Event\EmailHasNotBeenSent as EmailHasNotBeenSentEvent;
use BartoszBartniczak\EventSourcing\Shop\Email\Factory\Factory as EmailFactory;
use BartoszBartniczak\EventSourcing\Shop\Email\Sender\NullEmailSenderService;
use BartoszBartniczak\EventSourcing\Shop\Generator\ActivationTokenGenerator;
use BartoszBartniczak\EventSourcing\Shop\Order\Command\CreateOrder as CreateOrderCommand;
use BartoszBartniczak\EventSourcing\Shop\Order\Command\Handler\CreateOrder as CreateOrderCommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\Factory as OrderPositionsFactory;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\ProductIdStrategy;
use BartoszBartniczak\EventSourcing\Shop\Password\HashGenerator;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\FindProductByName as FindProductByNameCommand;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Command\Handler\FindProductByName as FindProductByNameCommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event\ProductHasNotBeenFound as ProductHasNotBeenFoundEvent;
use BartoszBartniczak\EventSourcing\Shop\Product\Repository\InMemoryRepository as InMemoryProductRepository;
use BartoszBartniczak\EventSourcing\Shop\User\Command\ActivateUser as ActivateUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\ActivateUser as ActivateUserCommandHandler;
use BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\LogInUser as LogInUserCommandHandler;
use BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\LogOutUser as LogOutUserCommandHandler;
use BartoszBartniczak\EventSourcing\Shop\User\Command\Handler\RegisterNewUser as RegisterNewUserCommandHandler;
use BartoszBartniczak\EventSourcing\Shop\User\Command\LogInUser as LogInUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Command\LogOutUser as LogOutUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Command\RegisterNewUser as RegisterNewUserCommand;
use BartoszBartniczak\EventSourcing\Shop\User\Factory\Factory as UserFactory;
use BartoszBartniczak\EventSourcing\Shop\User\Repository\InMemoryUserRepository as InMemoryUserRepository;
use BartoszBartniczak\EventSourcing\UUID\RamseyGeneratorAdapter;

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

/* Dependency Injection Container */

$uuidGenerator = new RamseyGeneratorAdapter();
$emailSenderService = new NullEmailSenderService();

$propertyNamingStrategy = new \JMS\Serializer\Naming\CamelCaseNamingStrategy();

$jmsSerializer = JMS\Serializer\SerializerBuilder::create()
    ->setPropertyNamingStrategy($propertyNamingStrategy)
    ->addMetadataDir(__DIR__ . '/config/serializer', "BartoszBartniczak\EventSourcing\Shop")
    ->addMetadataDir(__DIR__ . '/config/serializer', "BartoszBartniczak\EventSourcing")
    ->addMetadataDir(__DIR__ . '/config/serializer', "BartoszBartniczak")
    ->build();
$serializer = new JMSJsonSerializer($jmsSerializer, $propertyNamingStrategy);

$eventRepository = new InMemoryEventRepository($serializer);

$eventBus = new SimpleEventBus();
$eventBus->registerHandler(EmailHasNotBeenSentEvent::class, function (EmailHasNotBeenSentEvent $emailHasNotBeenSent) {
    // in real application you would like to try to resend email in here
});
$eventBus->registerHandler(ProductHasNotBeenFoundEvent::class, function (ProductHasNotBeenFoundEvent $productHasNotBeenFound) {
    // you can do something in here, E.g send email to product manager
});

$commandBus = new CommandBus($uuidGenerator, $eventRepository, $eventBus);
$commandBus->registerHandler(CreateNewBasketCommand::class, new CreateNewBasketHandler($uuidGenerator));
$commandBus->registerHandler(AddProductToTheBasketCommand::class, new AddProductToTheBasketHandler($uuidGenerator));
$commandBus->registerHandler(ChangeQuantityOfTheProduct::class, new ChangeQuantityOfTheProductHandler($uuidGenerator));
$commandBus->registerHandler(RemoveProductFromTheBasket::class, new RemoveProductFromTheBasketHandler($uuidGenerator));
$commandBus->registerHandler(RegisterNewUserCommand::class, new RegisterNewUserCommandHandler($uuidGenerator));
$commandBus->registerHandler(SendEmailCommand::class, new SendEmailCommandHandler($uuidGenerator));
$commandBus->registerHandler(ActivateUserCommand::class, new ActivateUserCommandHandler($uuidGenerator));
$commandBus->registerHandler(LogInUserCommand::class, new LogInUserCommandHandler($uuidGenerator));
$commandBus->registerHandler(FindProductByNameCommand::class, new FindProductByNameCommandHandler($uuidGenerator));
$commandBus->registerHandler(LogOutUserCommand::class, new LogOutUserCommandHandler($uuidGenerator));
$commandBus->registerHandler(CreateOrderCommand::class, new CreateOrderCommandHandler($uuidGenerator));
$commandBus->registerHandler(CloseBasketCommand::class, new CloseBasketCommandHandler($uuidGenerator));

$basketFactory = new BasketFactory($uuidGenerator);
$basketRepository = new BasketRepository($eventRepository, $basketFactory);
$userFactory = new UserFactory();
$userRepository = new InMemoryUserRepository($eventRepository, $userFactory);

$hashGenerator = new HashGenerator();

$productRepository = new InMemoryProductRepository();
$milkId = new ProductId($uuidGenerator->generate()->toNative());
$productRepository->save(new Product($milkId, 'Milk'));

$breadId = new ProductId($uuidGenerator->generate()->toNative());
$productRepository->save(new Product($breadId, 'Bread'));

$butterUuid = new ProductId($uuidGenerator->generate()->toNative());
$productRepository->save(new Product($butterUuid, 'Butter'));

$emailFactory = new EmailFactory($uuidGenerator);

$orderPositionsFactory = new OrderPositionsFactory($productRepository, new ProductIdStrategy());
/* Controller */

$registerUserCommand = new RegisterNewUserCommand('user@user.com', 'password', $emailSenderService, new ActivationTokenGenerator(), $uuidGenerator, $hashGenerator, $emailFactory->createEmpty());
$commandBus->execute($registerUserCommand);
$user = $userRepository->findUserByEmail('user@user.com');

$activateUserCommand = new ActivateUserCommand('user@user.com', 'xxx', $userRepository); //attempt of activating user account with wrong token
$commandBus->execute($activateUserCommand);

$activateUserCommand = new ActivateUserCommand('user@user.com', $user->getActivationToken(), $userRepository);
$commandBus->execute($activateUserCommand);

$activateUserCommand = new ActivateUserCommand('user@user.com', $user->getActivationToken(), $userRepository); //attempt of activating already activated account
$commandBus->execute($activateUserCommand);

$logInUserCommand = new LogInUserCommand('user@user.com', 'password', $hashGenerator, $userRepository);
$commandBus->execute($logInUserCommand);

$findProductByNameCommand = new FindProductByNameCommand($user, 'Milk', $productRepository);
$milk = $commandBus->execute($findProductByNameCommand);

$createNewBasket = new CreateNewBasketCommand($basketFactory, $user->getEmail());
$commandBus->execute($createNewBasket);
$basket = $basketRepository->findLastBasketByUserEmail($user->getEmail());

$addProductToTheBasket = new AddProductToTheBasketCommand($basket, $milk, 2.0);
$commandBus->execute($addProductToTheBasket);

$findProductByNameCommand = new FindProductByNameCommand($user, 'Bread', $productRepository);
$bread = $commandBus->execute($findProductByNameCommand);

$addProductToTheBasket = new AddProductToTheBasketCommand($basket, $bread, 1.0);
$commandBus->execute($addProductToTheBasket);

$findProductByNameCommand = new FindProductByNameCommand($user, 'Butter', $productRepository);
$butter = $commandBus->execute($findProductByNameCommand);

$addProductToTheBasket = new AddProductToTheBasketCommand($basket, $butter, 3.0);
$commandBus->execute($addProductToTheBasket);

$changeQuantityOfTheProduct = new ChangeQuantityOfTheProduct($basket, $butterUuid, 1.0);
$commandBus->execute($changeQuantityOfTheProduct);

$removeProductFromTheBasket = new RemoveProductFromTheBasket($basket, $breadId);
$commandBus->execute($removeProductFromTheBasket);

$logOutUserCommand = new LogOutUserCommand($user->getEmail(), $userRepository);
$commandBus->execute($logOutUserCommand);

$logInUserCommand = new LogInUserCommand('user@user.com', 'password', $hashGenerator, $userRepository);
$commandBus->execute($logInUserCommand);

try {
    $findProductByNameCommand = new FindProductByNameCommand($user, 'Cookies', $productRepository);
    $commandBus->execute($findProductByNameCommand);
} catch (CannotExecuteTheCommandException $cannotHandleTheCommandException) {
    dump("Display the error message", $cannotHandleTheCommandException);
}

$createOrderCommand = new CreateOrderCommand($uuidGenerator, $basket, $emailSenderService, $emailFactory->createEmpty(), $orderPositionsFactory);
$commandBus->execute($createOrderCommand);

///** Recreating the basket */
dump($eventRepository);
$user = $userRepository->findUserByEmail('user@user.com');
$basket = $basketRepository->findLastBasketByUserEmail($user->getEmail());
dump($user);
dump($basket);