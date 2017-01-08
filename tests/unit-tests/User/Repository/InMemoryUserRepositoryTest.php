<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Repository;


use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;
use BartoszBartniczak\EventSourcing\Shop\Event\Repository\InMemoryEventRepository;
use BartoszBartniczak\EventSourcing\Shop\Event\Serializer\Serializer;
use BartoszBartniczak\EventSourcing\Shop\User\Event\UserHasBeenRegistered;
use BartoszBartniczak\EventSourcing\Shop\User\Factory\Factory;
use BartoszBartniczak\EventSourcing\Shop\User\User;

class InMemoryUserRepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Repository\InMemoryUserRepository::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Repository\InMemoryUserRepository::findUserByEmail
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Repository\InMemoryUserRepository::getFilterEmail
     */
    public function testFindUserByEmail()
    {
        $event1 = $this->getMockBuilder(UserHasBeenRegistered::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getUserEmail',
                'getPasswordHash',
                'getPasswordSalt'
            ])
            ->setMockClassName('UserHasBeenRegistered')
            ->getMock();

        $event1->method('getUserEmail')->willReturn('user@user.com');
        $event1->method('getPasswordHash')->willReturn('password');
        $event1->method('getPasswordSalt')->willReturn('salt');
        /* @var $event1 UserHasBeenRegistered */

        $event2 = $this->getMockBuilder(UserHasBeenRegistered::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getUserEmail',
                'getPasswordHash',
                'getPasswordSalt'
            ])
            ->setMockClassName('UserHasBeenRegistered')
            ->getMock();
        $event2->method('getUserEmail')->willReturn('other@company.com');
        $event2->method('getPasswordHash')->willReturn('password');
        $event2->method('getPasswordSalt')->willReturn('salt');
        /* @var $event2 UserHasBeenRegistered */

        $json1 = stripslashes('{"event_family_name":"User","name":"Shop\\User\\Event\\UserHasBeenRegistered","event_id":{"uuid":"84c7ca36-335f-44a3-9c17-a34f1d8a1f8f"},"date_time":"2016-12-21T09:19:17+0100","user_email":"user@user.com","password_hash":"$2y$10$MDAwMTQ3NDktMThkNy00ZOTO6ITva4wDv6hA0GeLoip5HJjn1SuWy","password_salt":"00014749-18d7-4e68-98d5-56a2efd686d2"}');
        $json2 = stripslashes('{"event_family_name":"User","name":"Shop\\User\\Event\\UserHasBeenRegistered","event_id":{"uuid":"84c7ca36-335f-44a3-9c17-a34f1d8a1f8f"},"date_time":"2016-12-21T09:19:17+0100","user_email":"other@company.com","password_hash":"$2y$10$MDAwMTQ3NDktMThkNy00ZOTO6ITva4wDv6hA0GeLoip5HJjn1SuWy","password_salt":"00014749-18d7-4e68-98d5-56a2efd686d2"}');

        $serializer = $this->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'serialize',
                'deserialize',
                'getPropertyKey'
            ])
            ->getMockForAbstractClass();
        $serializer
            ->method('serialize')
            ->willReturnMap([
                [$event1, $json1],
                [$event2, $json2]
            ]);
        $serializer->method('getPropertyKey')->willReturn('event_family_name');
        $serializer->method('deserialize')->willReturnMap([
            [$json1, $event1],
            [$json2, $event2]
        ]);
        /* @var $serializer Serializer */

        $eventRepository = new InMemoryEventRepository($serializer);
        /* @var  $eventRepository InMemoryEventRepository */
        $eventRepository->saveEvent($event1);
        $eventRepository->saveEvent($event2);

        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $userMock->method('findHandleMethod')->willReturn('handleUserHasBeenRegistered');
        /* @var $userMock User */

        $userFactory = $this->getMockBuilder(Factory::class)
            ->setMethods(['createEmpty'])
            ->getMock();
        $userFactory->method('createEmpty')->willReturn($userMock);
        /* @var $userFactory Factory */

        $inMemoryUserRepository = $this->getMockBuilder(InMemoryUserRepository::class)
            ->setConstructorArgs([$eventRepository, $userFactory])
            ->setMethods(['createEmptyUserObject'])
            ->getMock();

        $inMemoryUserRepository->method('createEmptyUserObject')->willReturn($userMock);

        /* @var $inMemoryUserRepository InMemoryUserRepository */
        $user = $inMemoryUserRepository->findUserByEmail('user@user.com');
        $this->assertInstanceOf(UserRepository::class, $inMemoryUserRepository);
        $this->assertEquals('user@user.com', $user->getEmail());
        $this->assertEquals(1, $user->getCommittedEvents()->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\User\Repository\InMemoryUserRepository::findUserByEmail
     */
    public function testFindUserByEmailThrowsCannotFindUserException()
    {
        $this->expectException(CannotFindUserException::class);
        $this->expectExceptionMessage("User with email 'no@email.com' cannot be found.");

        $eventRepository = $this->getMockBuilder(InMemoryEventRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'find'
            ])
            ->getMock();
        $eventRepository->method('find')->willReturn(new EventStream());
        /* @var $eventRepository InMemoryEventRepository */

        $inMemoryUserRepository = new InMemoryUserRepository($eventRepository, new Factory());
        $inMemoryUserRepository->findUserByEmail('no@email.com');
    }

}
