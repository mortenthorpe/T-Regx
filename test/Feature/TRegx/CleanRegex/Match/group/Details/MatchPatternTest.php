<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\Details;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegate_group_map_detailsText()
    {
        // when
        $result = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('Foo Bar Car')
            ->group('lowercase')
            ->map(function (MatchGroup $group) {
                return $group->text();
            });

        // then
        $this->assertEquals(['oo', 'ar', 'ar'], $result);
    }

    /**
     * @test
     */
    public function shouldDelegate_group_flatMap_detailsTextOffsetAll()
    {
        // given
        $subject = 'XXX:abc YYY:efg ZZZ:ijk';

        // when
        $result = pattern('[A-Z]+:(?<lowercase>[a-z]+)')->match($subject)->group('lowercase')->flatMap(function (MatchGroup $group) {
            return ['word:' . $group->text() => 'offset:' . $group->offset(), $group->all()];
        });

        // then
        $expected = [
            'word:abc' => 'offset:4', ['abc', 'efg', 'ijk'],
            'word:efg' => 'offset:12', ['abc', 'efg', 'ijk'],
            'word:ijk' => 'offset:20', ['abc', 'efg', 'ijk'],
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function shouldDelegate_group_map_detailsOffset()
    {
        // given
        $subject = 'Foo Bar Car';

        // when
        $result = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->map(function (MatchGroup $group) {
            return $group->offset();
        });

        // then
        $this->assertEquals([1, 5, 9], $result);
    }

    /**
     * @test
     */
    public function shouldThrow_group_map_detailsText_forUnmatchedGroup()
    {
        // given
        $subject = 'Computer L Three Four';

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group 'lowercase', but the group was not matched");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->map(function (MatchGroup $group) {
            return $group->text();
        });
    }
}
