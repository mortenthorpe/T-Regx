<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\index;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReceive_first_detailsIndex()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->first(function (Match $match) {
                // when
                $index = $match->index();

                // then
                $this->assertEquals(0, $index);
            });
    }

    /**
     * @test
     */
    public function shouldReceive_findFirst_detailsIndex()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->findFirst(function (Match $match) {
                // when
                $index = $match->index();

                // then
                $this->assertEquals(0, $index);
            });
    }

    /**
     * @test
     * @dataProvider iteratingMatchMethods
     * @param string $method
     */
    public function shouldReceive_trio_detailsIndex(string $method)
    {
        // given
        $indexes = [];

        pattern('\d+')
            ->match('111-222-333')
            ->$method(function (Match $match) use (&$indexes) {
                // when
                $index = $match->index();
                // then
                $indexes[] = $index;
                // clean up for flatMap()
                return [];
            });

        // then
        $this->assertEquals([0, 1, 2], $indexes);
    }

    public function iteratingMatchMethods(): array
    {
        return [
            ['forEach'],
            ['map'],
            ['flatMap'],
        ];
    }
}
