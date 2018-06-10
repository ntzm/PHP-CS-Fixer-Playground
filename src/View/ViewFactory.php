<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\View;

use Jean85\PrettyVersions;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\ConfigFile;
use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Issue;
use PhpCsFixerPlayground\Wrapper\FixerWrapper;
use SebastianBergmann\Diff\Differ;
use Twig\Environment;

final class ViewFactory implements ViewFactoryInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var Differ
     */
    private $differ;

    /**
     * @var FixerFactory
     */
    private $fixerFactory;

    public function __construct(
        Environment $twig,
        Differ $differ,
        FixerFactory $fixerFactory
    ) {
        $this->twig = $twig;
        $this->differ = $differ;
        $this->fixerFactory = $fixerFactory;
    }

    public function make(
        Run $run,
        string $result,
        array $appliedFixers,
        array $deprecationMessages,
        ConfigFile $configFile,
        Issue $issue = null
    ): string {
        $availableFixers = array_map(
            function (FixerInterface $fixer): FixerWrapper {
                return new FixerWrapper($fixer);
            },
            $this->fixerFactory->registerBuiltInFixers()->getFixers()
        );

        $phpCsFixerVersion = ltrim(
            PrettyVersions
                ::getVersion('friendsofphp/php-cs-fixer')
                ->getPrettyVersion(),
            'v'
        );

        return $this->twig->render(
            'index.twig',
            [
                'code' => $run->getCode(),
                'fixers' => $run->getRules(),
                'indent' => $run->getIndent(),
                'lineEnding' => $run->getLineEnding(),
                'result' => $result,
                'appliedFixers' => $appliedFixers,
                'deprecationMessages' => $deprecationMessages,
                'configFile' => $configFile,
                'issue' => $issue,
                'availableFixers' => $availableFixers,
                'phpCsFixerVersion' => $phpCsFixerVersion,
                'diff' => $this->differ->diff($run->getCode(), $result),
            ]
        );
    }
}
