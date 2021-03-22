<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder\builder\alternation\pcre;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_bind()
    {
        // given
        $pattern = Pattern::builder()->pcre()->bind('%You/her, (are|is) @question (you|her)%', [
            'question' => ['Hello %5', 'Yes?:)']
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, (are|is) (?:Hello\ \%5|Yes\?\:\)) (you|her)%uXSD', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $pattern = Pattern::builder()->pcre()->inject('%You/her, (are|is) @ (you|her)%', [
            ['Hello %5', 'Yes?:)']
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, (are|is) (?:Hello\ \%5|Yes\?\:\)) (you|her)%uXSD', $pattern);
    }
}
