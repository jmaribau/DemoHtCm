<?php

declare(strict_types=1);

namespace App\Manager\Logger;

use App\Entity\User;

/**
 * Class UserLogger.
 */
class UserLogger extends BaseLogger
{
    public const SET = 'SET USER';
    public const UPDATE = 'UPDATE USER';
    public const DELETE = 'DELETE USER';

    /**
     * @param User $user
     */
    public function userSet(User $user): void
    {
        // $this->logger->info(self::SET . ': ', [$this->user->getUserName(), $this->user->getRoles()]);
        $this->logger->info(self::SET.': ');
        $this->logger->info($this->serializer->serialize($user, 'json'));
        $this->logger->info('');
    }

    /**
     * @param User $user
     */
    public function userUpdate(User $user): void
    {
        // $this->logger->info(self::UPDATE . ': ', [$this->user->getUserName(), $this->user->getRoles()]);
        $this->logger->info(self::UPDATE.': ');
        $this->logger->info($this->serializer->serialize($user, 'json'));
        $this->logger->info('');
    }

    /**
     * @param User $user
     */
    public function userDelete(User $user): void
    {
        // $this->logger->info(self::DELETE . ': ', [$this->user->getUserName(), $this->user->getRoles()]);
        $this->logger->info(self::DELETE.': ');
        $this->logger->info($this->serializer->serialize($user, 'json'));
        $this->logger->info('');
    }
}
