<?php

namespace PhpCsFixerPlayground;

use Hashids\Hashids;
use Hashids\HashidsInterface;
use League\Container\Container as BaseContainer;
use League\Container\ReflectionContainer;
use PDO;
use SebastianBergmann\Diff\Differ;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;
use Twig_Filter;
use Twig_Loader_Filesystem;
use Twig_Test;

final class Container
{
    private $base;

    public function __construct()
    {
        $this->base = new BaseContainer();
        $this->base->delegate(new ReflectionContainer());

        $this->registerRequest();
        $this->registerHashids();
        $this->registerPdo();
        $this->registerRunRepository();
        $this->registerTwigEnvironment();
        $this->registerViewFactory();
    }

    public function get(string $alias): object
    {
        return $this->base->get($alias);
    }

    private function registerRequest(): void
    {
        $this->base->add(Request::class, function (): Request {
            return Request::createFromGlobals();
        });
    }

    private function registerHashids(): void
    {
        $this->base->add(HashidsInterface::class, function (): HashidsInterface {
            return new Hashids(getenv('HASHIDS_SECRET'), 10);
        });
    }

    private function registerPdo(): void
    {
        $this->base->add(PDO::class, function (): PDO {
            $db = new PDO(
                sprintf('sqlite:%s', __DIR__.'/../database.sqlite'), null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );

            $db->exec('create table if not exists runs (id integer primary key autoincrement, code text not null, rules json not null)');

            return $db;
        });
    }

    private function registerRunRepository(): void
    {
        $this->base
            ->add(RunRepositoryInterface::class, RunRepository::class)
            ->withArgument(PDO::class)
            ->withArgument(HashidsInterface::class)
        ;
    }

    private function registerTwigEnvironment(): void
    {
        $this->base->add(Twig_Environment::class, function (): Twig_Environment {
            $loader = new Twig_Loader_Filesystem(__DIR__.'/../templates');
            $twig = new Twig_Environment($loader);

            $twig->addTest(new Twig_Test('instanceof', function (object $instance, string $class): bool {
                return $instance instanceof $class;
            }));

            $twig->addFilter(new Twig_Filter('format', function (string $string): string {
                return preg_replace('/`(.+?)`/', '<code>$1</code>', $string);
            }, ['pre_escape' => 'html', 'is_safe' => ['html']]));

            $twig->addFilter(new Twig_Filter('link_rules', function (array $rules): string {
                return implode(', ', array_map(function (string $rule): string {
                    return sprintf('<a href="#%s"><code>%s</code></a>', $rule, $rule);
                }, $rules));
            }, ['pre_escape' => 'html', 'is_safe' => ['html']]));

            return $twig;
        });
    }

    private function registerViewFactory(): void
    {
        $this->base
            ->add(ViewFactoryInterface::class, ViewFactory::class)
            ->withArgument(Twig_Environment::class)
            ->withArgument(Differ::class)
        ;
    }
}
