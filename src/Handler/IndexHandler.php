<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\ConfigFileGeneratorInterface;
use PhpCsFixerPlayground\ViewFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

final class IndexHandler implements HandlerInterface
{
    /**
     * @var ViewFactoryInterface
     */
    private $viewFactory;

    /**
     * @var ConfigFileGeneratorInterface
     */
    private $configFileGenerator;

    public function __construct(
        ViewFactoryInterface $viewFactory,
        ConfigFileGeneratorInterface $configFileGenerator
    ) {
        $this->viewFactory = $viewFactory;
        $this->configFileGenerator = $configFileGenerator;
    }

    public function __invoke(array $vars): Response
    {
        $code = "<?php\n\n";
        $indent = '    ';
        $lineEnding = '\n';
        $generatedConfig = $this->configFileGenerator->generate(
            [],
            $indent,
            $lineEnding
        );

        return new Response(
            $this->viewFactory->make(
                $code,
                [],
                $code,
                [],
                $indent,
                $lineEnding,
                $generatedConfig
            )
        );
    }
}
