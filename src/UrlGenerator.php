<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use PhpCsFixerPlayground\Entity\Run;

final class UrlGenerator implements UrlGeneratorInterface
{
    /** @var string */
    private $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function generateUrlForRun(Run $run): string
    {
        return "{$this->baseUrl}/run/{$run->getId()->toString()}";
    }
}
