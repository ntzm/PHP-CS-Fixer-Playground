<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersion;
use PhpCsFixerPlayground\PhpVersion\PhpVersion;

final class Issue
{
    /** @var string */
    private $runUrl;

    /** @var string */
    private $input;

    /** @var string */
    private $output;

    /** @var ConfigFile */
    private $configFile;

    /** @var PhpVersion */
    private $phpVersion;

    /** @var PhpCsFixerVersion */
    private $phpCsFixerVersion;

    public function __construct(
        string $runUrl,
        string $input,
        string $output,
        ConfigFile $configFile,
        PhpVersion $phpVersion,
        PhpCsFixerVersion $phpCsFixerVersion
    ) {
        $this->runUrl = $runUrl;
        $this->input = $input;
        $this->output = $output;
        $this->configFile = $configFile;
        $this->phpVersion = $phpVersion;
        $this->phpCsFixerVersion = $phpCsFixerVersion;
    }

    public function __toString(): string
    {
        return <<<ISSUE
For configuration or updating questions please read the README and UPGRADE documentation,
or visit: https://gitter.im/PHP-CS-Fixer

When reporting an issue (bug) please provide the following information:

#### The PHP version you are using (`$ php -v`):
=> {$this->phpVersion}

#### PHP CS Fixer version you are using (`$ php-cs-fixer -V`):
=> {$this->phpCsFixerVersion}

#### The command you use to run PHP CS Fixer:
=> {$this->runUrl}

#### The configuration file you are using, if any:
```php
{$this->configFile}
```

#### If applicable, please provide minimum samples of PHP code (as plain text, not screenshots):
 * before running PHP CS Fixer (no changes):
```php
{$this->input}
```

 * with unexpected changes applied when running PHP CS Fixer:
```php
{$this->output}
```

 * with the changes you expected instead:
```php
=> ....................................
```
ISSUE;
    }
}
