<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\get;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Match;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReceive_first_detailsGet()
    {
        // given
        pattern('Hello (?<one>there)')->match('Hello there, General Kenobi')->first(function (Match $match) {
            // when
            $group = $match->get('one');

            // then
            $this->assertSame('there', $group);
        });
    }

    /**
     * @test
     * @dataProvider patternAndSubject
     * @param string $pattern
     * @param string $subject
     */
    public function shouldThrow_first_detailsGet_forUnmatchedGroup(string $pattern, string $subject)
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'one', but the group was not matched");

        // given
        pattern($pattern)->match($subject)->first(function (Match $match) {
            // when
            $match->get('one');
        });
    }

    public function patternAndSubject(): array
    {
        return [
            ['Hello (?<one>there)?', 'Hello XX, General Kenobi'],
            ['Hello (?<one>there)?(?<two>XX)', 'Hello XX, General Kenobi'],
        ];
    }

    /**
     * @test
     */
    public function shouldThrow_first_detailsGet_forNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'two'");

        // given
        pattern('(?<one>hello)')->match('hello')->first(function (Match $match) {
            // when
            $match->get('two');
        });
    }

    /**
     * @test
     */
    public function shouldThrow_first_detailsGroup_forInvalidGroup()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index can only be an integer or a string, given: boolean (true)');

        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) {
                // when
                $match->group(true);
            });
    }
}
