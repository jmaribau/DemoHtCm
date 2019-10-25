<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

include 'config/bootstrap.php';

$key = array_search('--group', $_SERVER['argv'], true);
$filter = array_search('--filter', $_SERVER['argv'], true);

if (!(false !== $filter || (false !== $key && 'unit' === $_SERVER['argv'][(int) $key + 1]))) {
    echo 'Recreating and Seeding Database'.PHP_EOL;
    passthru('php bin/console doctrine:database:drop --force --env=test --if-exists');
    passthru('php bin/console doctrine:database:create --env=test');
    passthru('php bin/console doctrine:schema:create --env=test');
    passthru('php bin/console doctrine:fixtures:load -n --env=test');
    echo ' Done'.PHP_EOL;
}
