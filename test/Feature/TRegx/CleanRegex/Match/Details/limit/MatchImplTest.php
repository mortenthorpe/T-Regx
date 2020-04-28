<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\limit;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReceive_forEach_detailsIndex()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->forEach(function (Match $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(-1, $limit);
            });
    }

    /**
     * @test
     */
    public function shouldReceive_map_detailsLimit()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->map(function (Match $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(-1, $limit);
            });
    }

    /**
     * @test
     */
    public function shouldReceive_flatMap_detailsLimit()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->flatMap(function (Match $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(-1, $limit);

                // clean up
                return [];
            });
    }

    /**
     * @test
     */
    public function shouldReceive_first_detailsLimit()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->first(function (Match $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(1, $limit);
            });
    }

    /**
     * @test
     */
    public function shouldReceive_findFirst_detailsLimit()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->findFirst(function (Match $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(1, $limit);
            })
            ->orThrow();
    }
}
