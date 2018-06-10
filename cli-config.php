<?php

declare(strict_types=1);

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use PhpCsFixerPlayground\ResolveEntityManager;

require_once __DIR__.'/vendor/autoload.php';

return ConsoleRunner::createHelperSet((new ResolveEntityManager())());
