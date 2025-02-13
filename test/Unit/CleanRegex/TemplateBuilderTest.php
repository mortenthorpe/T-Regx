<?php
namespace Test\Unit\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use Test\Utils\Impl\ConstantDelimiter;
use Test\Utils\Impl\NoAlternation;
use Test\Utils\Impl\RawToken;
use Test\Utils\Impl\ThrowDelimiter;
use Test\Utils\Impl\ThrowToken;
use TRegx\CleanRegex\Exception\TemplateFormatException;
use TRegx\CleanRegex\TemplateBuilder;

class TemplateBuilderTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     * @dataProvider methods
     * @param string $method
     * @param array $arguments
     */
    public function shouldThrowForOverflowingLiteral(string $method, array $arguments): void
    {
        // given
        $template = new TemplateBuilder('^&&$', new ThrowDelimiter(), [new ThrowToken(), new ThrowToken(), new ThrowToken()]);

        // then
        $this->expectException(TemplateFormatException::class);
        $this->expectExceptionMessage('There are only 2 & tokens in template, but 3 builder methods were used');

        // when
        $template->$method(...$arguments);
    }

    /**
     * @test
     * @dataProvider methods
     * @param string $method
     * @param array $arguments
     */
    public function shouldThrowForMissingLiteral(string $method, array $arguments): void
    {
        // given
        $template = new TemplateBuilder('^&&$', new ThrowDelimiter(), [new ThrowToken()]);

        // then
        $this->expectException(TemplateFormatException::class);
        $this->expectExceptionMessage('There are 2 & tokens in template, but only 1 builder methods were used');

        // when
        $template->$method(...$arguments);
    }

    public function methods(): array
    {
        return [
            ['build', []],
            ['bind', [[]]],
            ['inject', [[]]],
        ];
    }

    /**
     * @test
     */
    public function shouldBuildBeImmutable(): void
    {
        // given
        $template = new TemplateBuilder('^&&$', new ConstantDelimiter(new NoAlternation()), [new RawToken('Z', "\1")]);

        // when
        $first = $template->literal('A');
        $second = $template->literal('B');
        $third = $template->literal('C');

        // then
        $this->assertSamePattern('^ZA$', $first->build());
        $this->assertSamePattern('^ZB$', $second->build());
        $this->assertSamePattern('^ZC$', $third->build());
    }

    /**
     * @test
     */
    public function shouldBuild(): void
    {
        // given
        $template = new TemplateBuilder('^&&$', new ConstantDelimiter(new NoAlternation()), [new RawToken('X', "\1")]);

        // when
        $first = $template->literal('{hi}');

        // then
        $this->assertSamePattern('^X\{hi\}$', $first->build());
    }
}
