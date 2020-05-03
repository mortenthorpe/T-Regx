<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\isInt;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReceive_first_detailsIsInt()
    {
        // given
        $result = pattern('\w+')
            ->match('11')
            ->first(function (Match $match) {
                // when
                return $match->isInt();
            });

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldReceive_first_detailsIsInt_forInvalidInteger()
    {
        // given
        pattern('(?<name>\w+)')
            ->match('Foo bar')
            ->first(function (Match $match) {
                // when
                $result = $match->isInt();

                // then
                $this->assertFalse($result);
            });
    }

    /**
     * @test
     */
    public function shouldReceive_first_detailsIsInt_forPseudoIntegerBecausePhpSucks()
    {
        // given
        $result = pattern('(.*)', 's')
            ->match('1e3')
            ->first(function (Match $match) {
                // when
                return $match->isInt();
            });

        // then
        $this->assertFalse($result);
    }
}
