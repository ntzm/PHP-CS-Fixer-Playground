<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\ConfigFile;
use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Indent;
use PhpCsFixerPlayground\LineEnding;
use PhpCsFixerPlayground\View\ViewFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

final class IndexHandler implements HandlerInterface
{
    /** @var ViewFactoryInterface */
    private $viewFactory;

    public function __construct(ViewFactoryInterface $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    public function __invoke(array $vars): Response
    {
        $code = "<?php\n\n";
        $indent = new Indent('    ');
        $lineEnding = new LineEnding("\n");

        $configFile = new ConfigFile([], $indent, $lineEnding);

        $run = new Run($code, [], $indent, $lineEnding);

        return new Response(
            $this->viewFactory->make($run, $code, [], [], $configFile),
        );
    }
}
