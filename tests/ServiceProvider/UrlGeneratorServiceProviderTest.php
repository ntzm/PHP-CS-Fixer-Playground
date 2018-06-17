<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\ServiceProvider;

use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\LineEnding;
use PhpCsFixerPlayground\ServiceProvider\UrlGeneratorServiceProvider;
use PhpCsFixerPlayground\UrlGenerator;
use PhpCsFixerPlayground\UrlGeneratorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\ServiceProvider\UrlGeneratorServiceProvider
 */
final class UrlGeneratorServiceProviderTest extends TestCase
{
    public function testProvides(): void
    {
        $provider = new UrlGeneratorServiceProvider();

        $this->assertTrue($provider->provides(UrlGeneratorInterface::class));
    }

    /** @runInSeparateProcess */
    public function testRegisters(): void
    {
        putenv('APP_URL=https://foo.com/bar');

        $provider = new UrlGeneratorServiceProvider();
        $provider->setContainer((new Container())->delegate(new ReflectionContainer()));
        $provider->register();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $provider->getContainer()->get(UrlGeneratorInterface::class);

        $this->assertInstanceOf(UrlGenerator::class, $urlGenerator);

        $run = new Run('<?php echo "hi";', [], '    ', LineEnding::fromVisible('\n'));

        $this->assertSame(
            "https://foo.com/bar/run/{$run->getId()->toString()}",
            $urlGenerator->generateUrlForRun($run)
        );
    }
}
