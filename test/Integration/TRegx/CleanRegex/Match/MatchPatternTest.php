<?php
namespace Test\Integration\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\NotMatched;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_all()
    {
        // when
        $matches = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->all();

        // then
        $this->assertEquals(['Foo Bar', 'Foo Bar', 'Foo Bar'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_only_2()
    {
        // when
        $matches = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->only(2);

        // then
        $this->assertEquals(['Foo Bar', 'Foo Bar'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_first()
    {
        // when
        $match = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->first();

        // then
        $this->assertEquals('Foo Bar', $match);
    }

    /**
     * @test
     */
    public function shouldGet_first_callback()
    {
        // when
        $value = pattern('[A-Z]+')->match('Foo, Bar, Top')->first(function () {
            return 'Different';
        });

        // then
        $this->assertEquals("Different", $value);
    }

    /**
     * @test
     */
    public function shouldGet_first_returnArbitraryType()
    {
        // when
        $value = pattern('[A-Z]+')
            ->match('Foo, Leszek Ziom, Dupa')
            ->first(function () {
                return new \stdClass();
            });

        // then
        $this->assertInstanceOf(\stdClass::class, $value);
    }

    /**
     * @test
     */
    public function shouldGet_first_matchAll()
    {
        // when
        pattern('(?<capital>[A-Z])(?<lowercase>[a-z]+)')
            ->match('Foo, Leszek Ziom, Bar')
            ->first(function (Match $match) {
                // then
                $this->assertEquals(['Foo', 'Leszek', 'Ziom', 'Bar'], $match->all());
            });
    }

    /**
     * @test
     */
    public function shouldGet_forFirst_orElse()
    {
        // when
        $value = pattern('[A-Z]+')
            ->match('Foo, Bar, Top')
            ->forFirst(function () {
                return 'Different';
            })
            ->orElse(function () {
                $this->assertFalse(true);
            });

        // then
        $this->assertEquals("Different", $value);
    }

    /**
     * @test
     */
    public function shouldGet_forFirst_orElse_groupsCount()
    {
        // when
        $value = pattern('[a-z]+')
            ->match('NOT MATCHING')
            ->forFirst(function () {
                $this->assertFalse(true);
            })
            ->orElse(function (NotMatched $notMatched) {
                // then
                $this->assertEquals(0, $notMatched->groupsCount());
                return 'Different';
            });

        // then
        $this->assertEquals("Different", $value);
    }

    /**
     * @test
     */
    public function shouldNotCall_iterate_onUnmatchedPattern()
    {
        // given
        pattern('dont match me')
            ->match('word')
            ->iterate(function () {
                // then
                $this->assertTrue(false, "This shouldn't be invoked");
            });

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldNotCall_first_OnUnmatchedPattern()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);

        // given
        pattern('dont match me')
            ->match('word')
            ->first(function () {
                // then
                $this->assertTrue(false, "This shouldn't be invoked");
            });
    }

    /**
     * @test
     */
    public function shouldGet_group_first()
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
    public function shouldGet_group_all()
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
    public function shouldGet_group_only_1()
    {
        // given
        $subject = 'D Computer';

        // when
        $groups1 = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->only(1);
        $groups2 = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->only(2);

        // then
        $this->assertEquals([null], $groups1);
        $this->assertEquals([null, 'omputer'], $groups2);
    }

    /**
     * @test
     */
    public function shouldGet_offsets()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd Computer L Three Four')
            ->offsets();

        // when
        $first = $offsets->first();
        $only1 = $offsets->only(1);
        $only2 = $offsets->only(2);
        $all = $offsets->all();

        // then
        $this->assertEquals(3, $first);
        $this->assertEquals([3], $only1);
        $this->assertEquals([3, 12], $only2);
        $this->assertEquals([3, 12, 14, 20], $all);
    }

    /**
     * @test
     */
    public function shouldGet_group_offsets()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd Computer L Three Four')
            ->group('lowercase')
            ->offsets();

        // when
        $first = $offsets->first();
        $only1 = $offsets->only(1);
        $only2 = $offsets->only(2);
        $all = $offsets->all();

        // then
        $this->assertEquals(4, $first);
        $this->assertEquals([4], $only1);
        $this->assertEquals([4, null], $only2);
        $this->assertEquals([4, null, 15, 21], $all);
    }

    /**
     * @test
     */
    public function shouldGet_group_offsets_onlyOne_null()
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
