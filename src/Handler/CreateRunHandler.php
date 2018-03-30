<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\Run;
use PhpCsFixerPlayground\RunRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreateRunHandler implements HandlerInterface
{
    /**
     * @var RunRepositoryInterface
     */
    private $runs;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var FixerFactory
     */
    private $fixerFactory;

    public function __construct(
        RunRepositoryInterface $runs,
        Request $request,
        FixerFactory $fixerFactory
    ) {
        $this->runs = $runs;
        $this->request = $request;
        $this->fixerFactory = $fixerFactory;
    }

    public function __invoke(array $vars): Response
    {
        $code = $this->request->request->get('code');

        $rules = $this->parseRules(
            $this->request->request->get('fixers')
        );

        $indent = $this->request->request->get('indent');
        $lineEnding = $this->request->request->get('line_ending');

        $run = new Run($code, $rules, $indent, $lineEnding);

        $run = $this->runs->save($run);

        return new RedirectResponse(
            sprintf('/%s', $run->getHash())
        );
    }

    private function parseRules(array $rules): array
    {
        $result = [];

        foreach ($rules as $name => $options) {
            if ($options['_enabled'] !== '_true') {
                continue;
            }

            unset($options['_enabled']);

            if (empty($options)) {
                $result[$name] = true;
            } else {
                $result[$name] = $this->parseOptions($options);
            }
        }

        return $result;
    }

    private function parseOptions(array $options): array
    {
        foreach ($options as &$option) {
            if ($option === '_true') {
                $option = true;
            } elseif ($option === '_false') {
                $option = false;
            } elseif (strpos($option, "\r\n") !== false) {
                $option = explode("\r\n", $option);
            }
        }

        return $options;
    }
}
