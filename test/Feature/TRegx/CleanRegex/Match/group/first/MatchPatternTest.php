<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\first;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_group_first()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first();

        // then
        $this->assertEquals('omputer', $groups);
    }

    /**
     * @test
     */
    public function shouldReturn_group_first_forEmptyGroup()
    {
        // given
        $subject = 'Foo NOT MATCH';

        // when
        $groups = pattern('Foo (?<bar>[a-z]*)')->match($subject)->group('bar')->first();

        // then
        $this->assertEquals('', $groups);
    }

    /**
     * @test
     */
    public function shouldReceive_group_first_detailsText()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first(function (MatchGroup $group) {
            $this->assertEquals('omputer', $group->text());
        });
    }

    /**
     * @test
     */
    public function shouldReceive_group_first_detailsAll()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first(function (MatchGroup $group) {
            $this->assertEquals(['omputer', null, 'hree', 'our'], $group->all());
        });
    }

    /**
     * @test
     */
    public function shouldReceive_group_first_detailsAsString()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first(function (string $group) {
            $this->assertEquals('omputer', $group);
        });
    }

    /**
     * @test
     */
    public function shouldThrow_group_first_forUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'lowercase' from the first match, but the group was not matched");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('lowercase')->first();
    }

    /**
     * @test
     */
    public function shouldThrow_group_first_onUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'lowercase' from the first match, but subject was not matched at all");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('123')->group('lowercase')->first();
    }

    /**
     * @test
     */
    public function shouldThrow_group_first_forNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->first();
    }

    /**
     * @test
     */
    public function shouldReturn_group_offsets_first()
    {
        // when
        $first = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd Computer L Three Four')
            ->group('lowercase')
            ->offsets()
            ->first();

        // then
        $this->assertEquals(4, $first);
    }
}
