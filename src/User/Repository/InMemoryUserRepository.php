<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\User\Repository;


use BartoszBartniczak\EventSourcing\Event\Repository\InMemoryEventRepository;
use BartoszBartniczak\EventSourcing\Shop\User\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\User\Factory\Factory;
use BartoszBartniczak\EventSourcing\Shop\User\User;

class InMemoryUserRepository implements UserRepository
{

    /**
     * @var InMemoryEventRepository
     */
    private $inMemoryEventRepository;

    /**
     * @var Factory
     */
    private $userFactory;

    /**
     * InMemoryUserRepository constructor.
     * @param InMemoryEventRepository $inMemoryEventRepository
     * @param Factory $userFactory
     */
    public function __construct(InMemoryEventRepository $inMemoryEventRepository, Factory $userFactory)
    {
        $this->inMemoryEventRepository = $inMemoryEventRepository;
        $this->userFactory = $userFactory;
    }

    /**
     * @param string $email
     * @return User
     */
    public function findUserByEmail(string $email): User
    {
        $eventStream = $this->inMemoryEventRepository->find(Event::FAMILY_NAME, ['user_email' => $this->getFilterEmail($email)]);

        if ($eventStream->isEmpty()) {
            throw new CannotFindUserException(sprintf("User with email '%s' cannot be found.", $email));
        }

        $user = $this->userFactory->createEmpty();
        $user->applyEventStream($eventStream);
        $user->commit();
        return $user;
    }

    /**
     * @param string $email
     * @return callable
     */
    protected function getFilterEmail(string $email): callable
    {
        return function ($eventData) use ($email) {

            $userEvent = $this->inMemoryEventRepository->getEventSerializer()->deserialize($eventData);
            /* @var $userEvent \Shop\User\Event\Event */

            if ($userEvent->getUserEmail() === $email) {
                return true;
            } else {
                return false;
            }


        };
    }

}