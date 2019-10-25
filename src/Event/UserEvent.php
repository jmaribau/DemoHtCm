<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class UserEvent.
 */
class UserEvent extends Event
{
    public const SET = 'user.set';
    public const UPDATE = 'user.update';
    public const DELETE = 'user.delete';

    /**
     * @var User
     */
    public $user;

    /**
     * UserEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
