<?php

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\ViewFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

final class IndexHandler implements HandlerInterface
{
    private $viewFactory;

    public function __construct(ViewFactoryInterface $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    public function __invoke(array $vars): Response
    {
        return new Response(
            $this->viewFactory->make("<?php\n\n", [], "<?php\n\n")
        );
    }
}
