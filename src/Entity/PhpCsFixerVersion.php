<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PhpCsFixerVersion
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $version;

    public function __construct(string $version)
    {
        $this->version = $version;
    }
}
