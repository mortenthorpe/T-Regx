<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\offset;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReceive_first_detailsOffset_batch()
    {
        // when
        pattern('\w{4,}')
            ->match('Cześć, Tomek')
            ->first(function (Match $match) {
                // when
                $offset = $match->offset();
                $byteOffset = $match->byteOffset();

                // then
                $this->assertEquals(7, $offset);
                $this->assertEquals(9, $byteOffset);
            });
    }

    /**
     * @test
     */
    public function shouldReceive_forEach_detailsOffset_batch()
    {
        // when
        pattern('\w{4,}')
            ->match('Cześć, Tomek i Kamil')
            ->forEach(function (Match $match) {
                if ($match->index() !== 1) return;

                // when
                $offset = $match->offset();
                $byteOffset = $match->byteOffset();

                // then
                $this->assertEquals(15, $offset);
                $this->assertEquals(17, $byteOffset);
            });
    }
}
