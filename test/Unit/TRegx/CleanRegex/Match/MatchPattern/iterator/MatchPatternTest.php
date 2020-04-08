<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\iterator;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_iterator()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard("([A-Z])?[a-z']+"), "Nice matching pattern");
        $matches = ['Nice', 'matching', 'pattern'];

        // when
        $iterator = $pattern->getIterator();

        // then
        foreach ($iterator as $index => $match) {
            // then
            $this->assertEquals($matches[$index], $match->text());
            $this->assertEquals($index, $match->index());
            $this->assertEquals('Nice matching pattern', $match->subject());
            $this->assertEquals(['Nice', 'matching', 'pattern'], $match->all());
        };
    }

    /**
     * @test
     */
    public function should_hasNext_matched()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard("[A-Z]+"), "Nice matching pattern");

        // when
        $iterator = $pattern->getIterator();

        // then
        $this->assertTrue($iterator->valid());
    }

    /**
     * @test
     */
    public function shouldNot_hasNext_unmatched()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard("[0-9]+"), "Nice matching pattern");

        // when
        $iterator = $pattern->getIterator();

        // then
        $this->assertFalse($iterator->valid());
    }
}
