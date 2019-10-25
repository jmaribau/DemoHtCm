<?php

declare(strict_types=1);

namespace App\Tests\Integration\Manager\Logger;

use App\Entity\Coffee;
use App\Entity\Order;
use App\Entity\User;
use App\Manager\Logger\CoffeeLogger;
use App\Manager\Logger\OrderLogger;
use Doctrine\ORM\EntityManager;
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
class OrderLoggerTest extends KernelTestCase
{
    public const LOG_FILE_PATH = 'var/log/test.app.log';

    /**
     * @var null|OrderLogger
     */
    private $orderLogger;
    /**
     * @var Order
     */
    private $order;

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

        $this->orderLogger = new OrderLogger($logger, $serializer, $security);

        file_put_contents(self::LOG_FILE_PATH, null);

        /** @var EntityManager $entityManager */
        $entityManager = self::$container->get('doctrine');
        /** @var Coffee coffee */
        $coffee = $entityManager->getRepository(Coffee::class)->find(2);
        /** @var User user */
        $user = $entityManager->getRepository(User::class)->find(2);

        $order = new Order();
        $order->setCoffee($coffee);
        $order->setUser($user);
        $order->setQuantity(10);
        $order->setAmount(100);
        $this->order = $order;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->orderLogger = null;
    }

    public function testOrderSet(): void
    {
        $this->orderLogger->orderSet($this->order);
        $logFileContent = file_get_contents(self::LOG_FILE_PATH);
        $this->assertContains(OrderLogger::SET, $logFileContent);
        $this->assertContains(CoffeeLogger::UPDATE, $logFileContent);
    }

    public function testOrderUpdate(): void
    {
        $this->orderLogger->orderUpdate($this->order);
        $logFileContent = file_get_contents(self::LOG_FILE_PATH);
        $this->assertContains($this->orderLogger::UPDATE, $logFileContent);
        $this->assertContains(CoffeeLogger::UPDATE, $logFileContent);
    }

    public function testOrderDelete(): void
    {
        $this->orderLogger->orderDelete($this->order);
        $logFileContent = file_get_contents(self::LOG_FILE_PATH);
        $this->assertContains($this->orderLogger::DELETE, $logFileContent);
        $this->assertContains(CoffeeLogger::UPDATE, $logFileContent);
    }
}
