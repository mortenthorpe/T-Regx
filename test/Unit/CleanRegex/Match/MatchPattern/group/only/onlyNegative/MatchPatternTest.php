<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\group\only\onlyNegative;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_forMatching()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'Nice Matching Pattern');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');

        // when
        $pattern->group('two')->only(-2);
    }

    /**
     * @test
     */
    public function shouldThrow_unmatchedGroups()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<hour>\d\d)?:(?<minute>\d\d)?'), 'First->11:__   Second->__:12   Third->13:32');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');

        // when
        $pattern->group('hour')->only(-2);
    }

    /**
     * @test
     */
    public function shouldThrow_onNotMatchedSubject()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'NOT MATCHING');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');

        // when
        $pattern->group('two')->only(-2);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonExistentGroup()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<existing>[a-z]+)'), 'matching');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $pattern->group('missing')->only(-2);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonExistentGroup_onNotMatchedSubject()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<existing>[a-z]+)'), 'NOT MATCHING');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $pattern->group('missing')->only(-2);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidGroupName()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<existing>[a-z]+)'), 'matching');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, given: '2invalid'");

        // when
        $pattern->group('2invalid')->only(-2);
    }
}
