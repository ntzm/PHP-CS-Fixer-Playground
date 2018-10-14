<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Entity
 * @ORM\Table(name="php_cs_fixer_versions")
 */
class PhpCsFixerVersion
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string", length=10)
     */
    private $number;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    public function __construct(string $number, string $name)
    {
        if (preg_match('/^\d+\.\d+\.\d+$/', $number) !== 1) {
            throw new InvalidArgumentException("Invalid PHP-CS-Fixer version $number");
        }

        $this->number = $number;
        $this->name = $name;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return "{$this->number} {$this->name}";
    }
}
