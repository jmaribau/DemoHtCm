<?php

declare(strict_types=1);

namespace App\Tests\Integration\Manager\Logger;

use App\Entity\User;
use App\Manager\Logger\UserLogger;
use JMS\Serializer\Serializer;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Security;

/**
 * @group integration
 * @covers
 *
 * @internal
 */
class UserLoggerTest extends KernelTestCase
{
    private const LOG_FILE_PATH = 'var/log/test.app.log';

    /**
     * @var null|UserLogger
     */
    private $userLogger;

    protected function setUp(): void
    {
        self::bootKernel();
        self::$kernel->getContainer();

        /** @var Logger $logger */
        $logger = self::$container->get('monolog.logger');
        /** @var Serializer $serializer */
        $serializer = self::$container->get('jms_serializer.serializer');
        /** @var Security $security */
        $security = self::$container->get('security.helper');

        $this->userLogger = new UserLogger($logger, $serializer, $security);

        file_put_contents(self::LOG_FILE_PATH, null);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->userLogger = null;
    }

    public function testUserSet(): void
    {
        $this->userLogger->userSet(new User());
        $logFileContent = file_get_contents(self::LOG_FILE_PATH);
        $this->assertContains($this->userLogger::SET, $logFileContent);
    }

    public function testUserUpdate(): void
    {
        $this->userLogger->userUpdate(new User());
        $logFileContent = file_get_contents(self::LOG_FILE_PATH);
        $this->assertContains($this->userLogger::UPDATE, $logFileContent);
    }

    public function testUserDelete(): void
    {
        $this->userLogger->userDelete(new User());
        $logFileContent = file_get_contents(self::LOG_FILE_PATH);
        $this->assertContains($this->userLogger::DELETE, $logFileContent);
    }
}
