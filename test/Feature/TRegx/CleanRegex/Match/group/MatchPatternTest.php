<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_group_all()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->all();

        // then
        $this->assertEquals(['omputer', null, 'hree', 'our'], $groups);
    }

    /**
     * @test
     */
    public function shouldReturn_group_all_onUnmatchedSubject()
    {
        // given
        $subject = 'NOT MATCHING';

        // when
        $all = pattern('[A-Z](?<lowercase>[a-z]+)')->match($subject)->group('lowercase')->all();

        // then
        $this->assertEmpty($all);
    }

    /**
     * @test
     */
    public function shouldReturn_group_only1_onUnmatchedSubject()
    {
        // given
        $subject = 'NOT MATCHING';

        // when
        $all = pattern('[A-Z](?<lowercase>[a-z]+)')->match($subject)->group('lowercase')->only(1);

        // then
        $this->assertEmpty($all);
    }

    /**
     * @test
     */
    public function shouldThrow_group_all_forNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->all();
    }

    /**
     * @test
     */
    public function shouldThrow_group_fluent_all_forNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->fluent()->all();
    }

    /**
     * @test
     */
    public function shouldThrow_group_findFirst_forNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->findFirst(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldThrow_group_only1_forNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->only(1);
    }

    /**
     * @test
     */
    public function shouldReturn_group_only()
    {
        // when
        $groups1 = pattern('[A-Z](?<lowercase>[a-z]+)?')->match('D Computer')->group('lowercase')->only(1);
        $groups2 = pattern('[A-Z](?<lowercase>[a-z]+)?')->match('D Computer')->group('lowercase')->only(2);

        // then
        $this->assertEquals([null], $groups1);
        $this->assertEquals([null, 'omputer'], $groups2);
    }

    /**
     * @test
     */
    public function shouldDelegate_group_filter_detailsText()
    {
        // when
        $groups = pattern('\d+(?<unit>kg|[cm]?m)')
            ->match('15mm 12kg 16m 17cm 27kg')
            ->group('unit')
            ->filter(function (MatchGroup $group) {
                return $group->text() !== "kg";
            });

        // then
        $this->assertEquals(['mm', 'm', 'cm'], $groups);
    }

    /**
     * @test
     */
    public function shouldDelegate_group_fluent_filter_all_detailsText()
    {
        // when
        $groups = pattern('\d+(?<unit>kg|[cm]?m)')
            ->match('15mm 12kg 16m 17cm 27kg')
            ->group('unit')
            ->fluent()
            ->filter(function (MatchGroup $group) {
                return $group->text() !== "kg";
            })
            ->all();

        // then
        $this->assertEquals(['mm', 'm', 'cm'], $groups);
    }

    /**
     * @test
     */
    public function shouldDelegate_group_fluent_map_all_detailsText()
    {
        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('D Computer')
            ->group('lowercase')
            ->fluent()
            ->map(function (MatchGroup $group) {
                if ($group->matched()) {
                    return $group->text();
                }
                return "unmatched";
            })
            ->all();

        // then
        $this->assertEquals(['unmatched', 'omputer'], $groups);
    }

    /**
     * @test
     */
    public function shouldReturn_group_offsets_mixed()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd Computer L Three Four')
            ->group('lowercase')
            ->offsets();

        // when
        $only1 = $offsets->only(1);
        $only2 = $offsets->only(2);
        $all = $offsets->all();

        // then
        $this->assertEquals([4], $only1);
        $this->assertEquals([4, null], $only2);
        $this->assertEquals([4, null, 15, 21], $all);
    }

    /**
     * @test
     */
    public function shouldReturn_group_offsets_only1()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd L Three Four')
            ->group('lowercase')
            ->offsets();

        // when
        $only1 = $offsets->only(1);

        // then
        $this->assertEquals([null], $only1);
    }
}
