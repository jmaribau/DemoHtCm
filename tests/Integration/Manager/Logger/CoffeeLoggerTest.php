<?php

declare(strict_types=1);

namespace App\Tests\Integration\Manager\Logger;

use App\Entity\Coffee;
use App\Manager\Logger\CoffeeLogger;
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
class CoffeeLoggerTest extends KernelTestCase
{
    private const LOG_FILE_PATH = 'var/log/test.app.log';

    /**
     * @var null|CoffeeLogger
     */
    private $coffeeLogger;

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

        $this->coffeeLogger = new CoffeeLogger($logger, $serializer, $security);

        file_put_contents(self::LOG_FILE_PATH, null);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->coffeeLogger = null;
    }

    public function testCoffeeSet(): void
    {
        /** @var Coffee $coffee */
        $coffee = new Coffee();
        $this->coffeeLogger->coffeeSet($coffee);
        $logFileContent = file_get_contents(self::LOG_FILE_PATH);
        $this->assertContains($this->coffeeLogger::SET, $logFileContent);
    }

    public function testCoffeeUpdate(): void
    {
        $this->coffeeLogger->coffeeUpdate(new Coffee());
        $logFileContent = file_get_contents(self::LOG_FILE_PATH);
        $this->assertContains($this->coffeeLogger::UPDATE, $logFileContent);
    }

    public function testCoffeeDelete(): void
    {
        $this->coffeeLogger->coffeeDelete(new Coffee());
        $logFileContent = file_get_contents(self::LOG_FILE_PATH);
        $this->assertContains($this->coffeeLogger::DELETE, $logFileContent);
    }

    public function testCoffeeOutofStock(): void
    {
        $this->coffeeLogger->coffeeOutOfStock();
        $logFileContent = file_get_contents(self::LOG_FILE_PATH);
        $this->assertContains($this->coffeeLogger::STOCK, $logFileContent);
    }
}
