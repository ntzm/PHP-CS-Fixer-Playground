<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use PhpCsFixer\Console\Application;
use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;

final class PhpCsFixerVersionFactory implements PhpCsFixerVersionFactoryInterface
{
    public function make(): PhpCsFixerVersion
    {
        return new PhpCsFixerVersion(Application::VERSION, Application::VERSION_CODENAME);
    }
}
