<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\CompositePatternMapper;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Pattern;

class CompositePatternMapperTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreate()
    {
        // given
        $mapper = new CompositePatternMapper([
            '[A-Z]+',
            Pattern::of('[A-Z0-9]+'),
            Pattern::pcre('/[A-Z+]/i'),
            pattern('[A-Za-z]+', 'u'),
        ]);

        // when
        $patterns = $mapper->createPatterns();

        // then
        $expected = [
            '/[A-Z]+/uXSD',
            '/[A-Z0-9]+/uXSD',
            '/[A-Z+]/i',
            '/[A-Za-z]+/u'
        ];
        $actual = array_map(function (InternalPattern $pattern) {
            return $pattern->pattern;
        }, $patterns);
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidPattern()
    {
        // given
        $mapper = new CompositePatternMapper([new stdClass()]);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("CompositePattern only accepts type PatternInterface or string, but stdClass given");

        // when
        $mapper->createPatterns();
    }

    /**
     * @test
     */
    public function shouldThrow_string_onTrailingBackslash()
    {
        // given
        $mapper = new CompositePatternMapper([
            'pattern',
            'pattern\\'
        ]);

        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');

        // when
        $mapper->createPatterns();
    }
}
