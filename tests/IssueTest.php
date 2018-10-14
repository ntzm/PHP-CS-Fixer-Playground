<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use PhpCsFixerPlayground\ConfigFile;
use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;
use PhpCsFixerPlayground\Indent;
use PhpCsFixerPlayground\Issue;
use PhpCsFixerPlayground\LineEnding;
use PhpCsFixerPlayground\PhpVersion\PhpVersion;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\Issue
 */
final class IssueTest extends TestCase
{
    public function test(): void
    {
        $expected = <<<'ISSUE'
For configuration or updating questions please read the README and UPGRADE documentation,
or visit: https://gitter.im/PHP-CS-Fixer

When reporting an issue (bug) please provide the following information:

#### The PHP version you are using (`$ php -v`):
=> 7.2.6

#### PHP CS Fixer version you are using (`$ php-cs-fixer -V`):
=> 2.12.1 Long Journey

#### The command you use to run PHP CS Fixer:
=> https://foo.com/bar

#### The configuration file you are using, if any:
```php
<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return Config::create()
    ->setRiskyAllowed(true)
    ->setIndent('    ')
    ->setLineEnding("\n")
    ->setRules([
        'foo' => true,
    ])
    ->setFinder(
        Finder::create()->in(__DIR__)
    )
;
```

#### If applicable, please provide minimum samples of PHP code (as plain text, not screenshots):
 * before running PHP CS Fixer (no changes):
```php
<?php echo "hi";
```

 * with unexpected changes applied when running PHP CS Fixer:
```php
<?php echo 'hi';
```

 * with the changes you expected instead:
```php
=> ....................................
```
ISSUE;

        $configFile = new ConfigFile(
            ['foo' => true],
            new Indent('    '),
            LineEnding::fromVisible('\n')
        );

        $issue = new Issue(
            'https://foo.com/bar',
            '<?php echo "hi";',
            "<?php echo 'hi';",
            $configFile,
            new PhpVersion('7.2.6'),
            new PhpCsFixerVersion('2.12.1', 'Long Journey')
        );

        $this->assertSame($expected, $issue->__toString());
    }
}
