<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;

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

            if (file_exists($path)) {
                require $path;
            } else {
                die($path);
            }
        }, true, true);
    }
}
