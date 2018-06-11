<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Handler;

use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Handler\CreateRunHandler;
use PhpCsFixerPlayground\ParseRulesFromRequestInterface;
use PhpCsFixerPlayground\Run\RunRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \PhpCsFixerPlayground\Handler\CreateRunHandler
 */
final class CreateRunHandlerTest extends TestCase
{
    public function test(): void
    {
        /** @var RunRepositoryInterface|MockObject $runs */
        $runs = $this->createMock(RunRepositoryInterface::class);
        $runs
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Run $run): bool {
                return $run->getCode() === '<?php echo "hi";'
                    && $run->getRules() === ['bar' => true]
                    && $run->getIndent() === '    '
                    && $run->getLineEnding()->getVisible() === '\n';
            }))
        ;

        /** @var Request|MockObject $request */
        $request = $this->createMock(Request::class);
        $request->request = new ParameterBag();
        $request->request->add([
            'code' => '<?php echo "hi";',
            'fixers' => ['foo' => true],
            'indent' => '    ',
            'line_ending' => '\n',
        ]);

        /** @var ParseRulesFromRequestInterface|MockObject $parseRulesFromRequest */
        $parseRulesFromRequest = $this->createMock(ParseRulesFromRequestInterface::class);
        $parseRulesFromRequest
            ->expects($this->once())
            ->method('__invoke')
            ->with(['foo' => true])
            ->willReturn(['bar' => true])
        ;

        $handler = new CreateRunHandler($runs, $request, $parseRulesFromRequest);

        /** @var RedirectResponse $response */
        $response = $handler([]);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertRegExp('/^\/run\/[a-f0-9-]+$/', $response->getTargetUrl());
    }
}
