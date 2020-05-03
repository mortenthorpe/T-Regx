<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group\isInt;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\Match;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegate_first_detailsGroupIsInt_byIndex()
    {
        // given
        $result = pattern('(?<name>-?\w+)')
            ->match('11')
            ->first(function (Match $match) {
                // when
                return $match->group(1)->isInt();
            });

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldDelegate_first_detailsGroupIsInt_byIndex_forPseudoIntegerBecausePhpSucks()
    {
        // given
        $result = pattern('(.*)', 's')
            ->match('1e3')
            ->first(function (Match $match) {
                // when
                return $match->group(1)->isInt();
            });

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldReceive_forEach_detailsGroupIsInt()
    {
        // given
        pattern('(?<value>\d+)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->forEach(function (Match $match) {
                // when
                $isInt = $match->group('value')->isInt();

                // then
                $this->assertTrue($isInt);
            });
    }

    /**
     * @test
     */
    public function shouldReceive_map_detailsGroupIsInt_byIndex()
    {
        // given
        pattern('(?<value>\d+)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->map(function (Match $match) {
                // when
                $isInt = $match->group(1)->isInt();

                // then
                $this->assertTrue($isInt);
            });
    }

    /**
     * @test
     */
    public function shouldReceive_first_detailsGroupIsInt_forInvalidInteger()
    {
        // given
        pattern('(?<name>\w+)')
            ->match('Foo bar')
            ->first(function (Match $match) {
                // when
                $result = $match->group('name')->isInt();

                // then
                $this->assertFalse($result);
            });
    }

    /**
     * @test
     */
    public function shouldDelegate_first_detailsGroupIsInt_byIndex_forInvalidInteger()
    {
        // given
        pattern('(?<name>\w+)')
            ->match('Foo bar')
            ->first(function (Match $match) {
                // when
                $result = $match->group(1)->isInt();

                // then
                $this->assertFalse($result);
            });
    }

    /**
     * @test
     */
    public function shouldThrow_first_detailsGroupIsInt_forUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call isInt() for group 'missing', but the group was not matched");

        // given
        pattern('(?<name>\w+)(?<missing>\d+)?')
            ->match('Foo bar')
            ->first(function (Match $match) {
                // when
                return $match->group('missing')->isInt();
            });
    }
}
