<?php

namespace App\Observers;

use App\NotificationType;
use App\Repositories\UserRepositoryInterface;
use App\User;

class UserObserver
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function created(User $user)
    {
        $this->userRepository->addNotification($user, NotificationType::UPCOMING_EVENTS);
        $this->userRepository->addNotification($user, NotificationType::GUEST_JOINED);
    }
}
