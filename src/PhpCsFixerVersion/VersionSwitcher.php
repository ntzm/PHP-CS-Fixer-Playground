<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;
use RuntimeException;

final class VersionSwitcher implements VersionSwitcherInterface
{
    /**
     * @var string
     */
    private $baseDir;

    public function __construct(string $baseDir)
    {
        $this->baseDir = $baseDir;
    }

    public function switchTo(PhpCsFixerVersion $version): void
    {
        $this->checkNoClassesAlreadyAutoloaded();

        spl_autoload_register(function (string $class) use ($version): void {
            if (!$this->isPhpCsFixerClass($class)) {
                return;
            }

            $path = sprintf(
                $this->baseDir.'/%s/%s.php',
                $version->getVersion(),
                str_replace('\\', '/', substr($class, 11))
            );

            if (!file_exists($path)) {
                throw new RuntimeException(
                    sprintf(
                        'Cannot find class %s on PHP-CS-Fixer version %s, tried file %s',
                        $class,
                        $version->getVersion(),
                        $path
                    )
                );
            }

            include $path;
        }, true, true);
    }

    private function checkNoClassesAlreadyAutoloaded(): void
    {
        foreach (get_declared_classes() as $class) {
            if ($this->isPhpCsFixerClass($class)) {
                throw new RuntimeException(
                    sprintf('Class %s already autoloaded', $class)
                );
            }
        }
    }

    private function isPhpCsFixerClass(string $class): bool
    {
        return strpos($class, 'PhpCsFixer\\') === 0;
    }
}
