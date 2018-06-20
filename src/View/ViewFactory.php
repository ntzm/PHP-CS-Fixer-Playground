<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\View;

use PhpCsFixer\Console\Application;
use PhpCsFixerPlayground\ConfigFile;
use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Issue;
use PhpCsFixerPlayground\Wrapper\FixerCollectionFactoryInterface;
use SebastianBergmann\Diff\Differ;
use Twig\Environment;

final class ViewFactory implements ViewFactoryInterface
{
    /** @var Environment */
    private $twig;

    /** @var Differ */
    private $differ;

    /** @var FixerCollectionFactoryInterface */
    private $fixerCollectionFactory;

    public function __construct(
        Environment $twig,
        Differ $differ,
        FixerCollectionFactoryInterface $fixerCollectionFactory
    ) {
        $this->twig = $twig;
        $this->differ = $differ;
        $this->fixerCollectionFactory = $fixerCollectionFactory;
    }

    public function make(
        Run $run,
        string $result,
        array $appliedFixers,
        array $deprecationMessages,
        ConfigFile $configFile,
        Issue $issue = null
    ): string {
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
                'availableFixers' => $this->fixerCollectionFactory->all(),
                'phpCsFixerVersion' => Application::VERSION,
                'diff' => $this->differ->diff($run->getCode(), $result),
            ]
        );
    }
}
