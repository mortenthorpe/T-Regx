<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder\builder;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_prepared()
    {
        // given
        $pattern = Pattern::builder()->prepare(['You/her, (are|is) ', ['real? (or are you not real?)'], ' (you|her)']);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('#You/her, (are|is) real\?\ \(or\ are\ you\ not\ real\?\) (you|her)#uXSD', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_bind()
    {
        // given
        $pattern = Pattern::builder()->bind('You/her, (are|is) @question (you|her)', [
            'question' => 'real? (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('#You/her, (are|is) real\?\ \(or\ are\ you\ not\ real\?\) (you|her)#uXSD', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $pattern = Pattern::builder()->inject('You/her, (are|is) @ (you|her)', [
            'real? (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('#You/her, (are|is) real\?\ \(or\ are\ you\ not\ real\?\) (you|her)#uXSD', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_bind_DefaultFlags()
    {
        // given
        $pattern = Pattern::builder()->template('%You/her, \s (her)%')->bind([]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('#%You/her, \s (her)%#uXSD', $pattern);
    }
}
