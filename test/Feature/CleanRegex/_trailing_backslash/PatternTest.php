<?php
namespace Test\Feature\TRegx\CleanRegex\_trailing_backslash;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider entryPoints
     * @param callable $entryPoint
     */
    public function shouldThrow_forTrailingBackslash(callable $entryPoint): void
    {
        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');

        // when
        $entryPoint();
    }

    public function entryPoints(): array
    {
        return [
            'Pattern::of()'                 => [function () {
                return Pattern::of('Foo \\');
            }],
            'Pattern::prepare()'            => [function () {
                return Pattern::prepare(['Foo \\']);
            }],
            'Pattern::inject()'             => [function () {
                return Pattern::inject('Foo \\', []);
            }],
            'Pattern::bind()'               => [function () {
                return Pattern::bind('Foo \\', []);
            }],
            'Pattern::compose()'            => [function () {
                return Pattern::compose(['Foo & \\']);
            }],
            'Pattern::template()->builder()->literal()->build()' => [function () {
                return Pattern::template('Foo & \\')->builder()->literal('&')->build();
            }],
            'Pattern::template()->builder()->literal()->inject()' => [function () {
                return Pattern::template('Foo & \\')->builder()->literal('&')->inject([]);
            }],
            'Pattern::template()->builder()->literal()->bind()' => [function () {
                return Pattern::template('Foo & \\')->builder()->literal('&')->bind([]);
            }],
        ];
    }

    /**
     * @test
     * @dataProvider templateEntryPoints
     * @param callable $entryPoint
     * @param string $message
     */
    public function shouldThrow_template_forTrailingBackslash(callable $entryPoint, string $message): void
    {
        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage($message);

        // when
        $entryPoint();
    }

    public function templateEntryPoints(): array
    {
        return [
            'Pattern::mask()'             => [
                function () {
                    return Pattern::mask('Foo%', ['%' => '()\\']);
                },
                "Malformed pattern '()\' assigned to keyword '%'"
            ],
            'Pattern::template()->mask()' => [
                function () {
                    return Pattern::template('Foo &')->mask('w', ['w' => '\\']);
                },
                "Malformed pattern '\' assigned to keyword 'w'"
            ],

            'Pattern::template()->builder()->mask()->build()' => [
                function () {
                    return Pattern::template('Foo &')->builder()->mask('w', ['w' => '\\'])->build();
                },
                "Malformed pattern '\' assigned to keyword 'w'"
            ],
        ];
    }
}
