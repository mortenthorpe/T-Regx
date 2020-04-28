<?php
namespace Test\Feature\TRegx\CleanRegex\Match\fluent\_stream;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegate_fluent_map_all_keepIndexes()
    {
        // given
        $indexes = pattern("\w+")
            ->match("Foo, Bar, Lorem")
            ->fluent()
            ->map(function (Match $match) {
                return $match->index();
            })
            ->all();

        // then
        $this->assertEquals([0, 1, 2], $indexes);
    }

    /**
     * @test
     */
    public function shouldReceive_fluent_map_all_detailsLimit()
    {
        // given
        pattern("\w+")->match("Foo, Bar")->fluent()
            ->map(function (Match $match) {
                // then
                $this->assertEquals(-1, $match->limit());
            })
            ->all();
    }

    /**
     * @test
     */
    public function shouldPreserveUserData_filter_fluent_forEach()
    {
        // given
        pattern("\w+")
            ->match("Foo, Bar, Lorem")
            ->filter(function (Match $match) {
                // when
                $match->setUserData('Foo');

                // cleanup
                return true;
            })
            ->fluent()
            ->forEach(function (Match $match) {
                $this->assertEquals('Foo', $match->getUserData());
            });
    }

    /**
     * @test
     */
    public function shouldDelegate_fluent_map_all_detailsAll()
    {
        // given
        $indexes = pattern("\w+")
            ->match("Foo, Bar, Lorem")
            ->fluent()
            ->map(function (Match $match) {
                return $match->all();
            })
            ->all();

        // then
        $value = ['Foo', 'Bar', 'Lorem'];
        $this->assertEquals([$value, $value, $value], $indexes);
    }

    /**
     * @test
     */
    public function shouldDelegate_fluent_first_keepIndexes()
    {
        // given
        pattern("\w+")->match("Foo, Bar")->fluent()->first(function (Match $match) {
            // then
            $this->assertEquals(0, $match->index());
        });
    }

    /**
     * @test
     */
    public function shouldDelegate_fluent_first_detailsLimit()
    {
        // given
        pattern("\w+")->match("Foo, Bar")->fluent()->first(function (Match $match) {
            $this->assertEquals(1, $match->limit());
        });
    }

    /**
     * @test
     */
    public function shouldPreserveUserData_filter_fluent_first()
    {
        // given
        pattern("\w+")
            ->match("Foo, Bar, Lorem")
            ->filter(function (Match $match) {
                // when
                $match->setUserData('Foo');

                // cleanup
                return true;
            })
            ->fluent()
            ->first(function (Match $match) {
                $this->assertEquals('Foo', $match->getUserData());
            });
    }

    /**
     * @test
     */
    public function shouldDelegate_fluent_first_detailsAll()
    {
        // given
        $indexes = pattern("\w+")
            ->match("Foo, Bar, Lorem")
            ->fluent()
            ->first(function (Match $match) {
                return $match->all();
            });

        // then
        $this->assertEquals(['Foo', 'Bar', 'Lorem'], $indexes);
    }
}
