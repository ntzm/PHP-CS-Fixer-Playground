<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use Hashids\Hashids;
use Hashids\HashidsInterface;
use PDO;
use PhpCsFixerPlayground\ConfigFileGenerator;
use PhpCsFixerPlayground\ConfigFileGeneratorInterface;
use PhpCsFixerPlayground\Container;
use PhpCsFixerPlayground\Fixer\Fixer;
use PhpCsFixerPlayground\Fixer\FixerInterface;
use PhpCsFixerPlayground\RunRepository;
use PhpCsFixerPlayground\RunRepositoryInterface;
use PhpCsFixerPlayground\View\ViewFactory;
use PhpCsFixerPlayground\View\ViewFactoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

/**
 * @covers \PhpCsFixerPlayground\Container
 */
final class ContainerTest extends TestCase
{
    public function testGetRequest(): void
    {
        $_GET['foo'] = 'bar';

        $container = new Container();

        /** @var Request $request */
        $request = $container->get(Request::class);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame('bar', $request->query->get('foo'));
    }

    public function testGetHashids(): void
    {
        $container = new Container();

        $this->assertInstanceOf(
            Hashids::class,
            $container->get(HashidsInterface::class)
        );
    }

    public function testGetPdo(): void
    {
        $container = new Container();

        $this->assertInstanceOf(PDO::class, $container->get(PDO::class));
    }

    public function testGetRunRepository(): void
    {
        $container = new Container();

        $this->assertInstanceOf(
            RunRepository::class,
            $container->get(RunRepositoryInterface::class)
        );
    }

    public function testGetTwigEnvironment(): void
    {
        $container = new Container();

        $this->assertInstanceOf(
            Environment::class,
            $container->get(Environment::class)
        );
    }

    public function testGetViewFactory(): void
    {
        $container = new Container();

        $this->assertInstanceOf(
            ViewFactory::class,
            $container->get(ViewFactoryInterface::class)
        );
    }

    public function testGetFixer(): void
    {
        $container = new Container();

        $this->assertInstanceOf(
            Fixer::class,
            $container->get(FixerInterface::class)
        );
    }

    public function testGetConfigFileGenerator(): void
    {
        $container = new Container();

        $this->assertInstanceOf(
            ConfigFileGenerator::class,
            $container->get(ConfigFileGeneratorInterface::class)
        );
    }
}
