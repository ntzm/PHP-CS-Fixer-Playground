<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;
use RuntimeException;

final class VersionSwitcher implements VersionSwitcherInterface
{
    public function switchTo(PhpCsFixerVersion $version): void
    {
        spl_autoload_register(function (string $class) use ($version): void {
            if (strpos($class, 'PhpCsFixer\\') !== 0) {
                return;
            }

            $path = sprintf(
                __DIR__.'/../../data/php-cs-fixer-versions/%s/%s.php',
                $version->getVersion(),
                str_replace('\\', DIRECTORY_SEPARATOR, substr($class, 11))
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
}
