<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @covers
 *
 * @internal
 */
class UserTest extends TestCase
{
    public function testUserCreate(): void
    {
        $user = new User();

        $this->assertEquals(User::class, get_class($user));
    }

    public function testUserCheckProperties(): void
    {
        $user = new User();
        $user->setUsername('Name');
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertEquals('string', gettype($user->getUsername()));
        $this->assertEquals('Name', $user->getUsername());

        $this->assertEquals('array', gettype($user->getRoles()));
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }
}
