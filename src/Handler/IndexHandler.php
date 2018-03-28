<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\ViewFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

final class IndexHandler implements HandlerInterface
{
    /**
     * @var ViewFactoryInterface
     */
    private $viewFactory;

    public function __construct(ViewFactoryInterface $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    public function __invoke(array $vars): Response
    {
        $code = "<?php\n\n";

        return new Response(
            $this->viewFactory->make($code, [], $code, '    ', "\n")
        );
    }
}
