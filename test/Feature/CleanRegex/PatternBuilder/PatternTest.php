<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_prepared()
    {
        // given
        $pattern = Pattern::prepare(['You/her, (are|is) ', ['real? (or are you not real?)'], ' (you|her)']);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertEquals('#You/her, (are|is) real\?\ \(or\ are\ you\ not\ real\?\) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_bind()
    {
        // given
        $pattern = Pattern::bind('You/her, (are|is) @question (you|her)', [
            'question' => 'real? (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertEquals('#You/her, (are|is) real\?\ \(or\ are\ you\ not\ real\?\) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $pattern = Pattern::inject('You/her, (are|is) @ (you|her)', [
            'real? (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertEquals('#You/her, (are|is) real\?\ \(or\ are\ you\ not\ real\?\) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_compose()
    {
        // given
        $pattern = Pattern::compose([
            pattern('^Fro'),
            Pattern::of('rod'),
            Pattern::pcre('/do$/'),
        ]);

        // when
        $matches = $pattern->allMatch('Frodo');

        // then
        $this->assertTrue($matches);
    }
}