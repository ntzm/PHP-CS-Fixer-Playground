<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Indent;
use PhpCsFixerPlayground\LineEnding;
use PhpCsFixerPlayground\UrlGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\UrlGenerator
 */
final class UrlGeneratorTest extends TestCase
{
    public function testGenerateUrlForRun(): void
    {
        $run = new Run(
            '<?php echo "hi";',
            [],
            new Indent('    '),
            LineEnding::fromVisible('\n')
        );

        $urlGenerator = new UrlGenerator('https://foobar.com/baz');

        $this->assertSame(
            "https://foobar.com/baz/run/{$run->getId()->toString()}",
            $urlGenerator->generateUrlForRun($run)
        );
    }
}
