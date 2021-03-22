<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder\builder\pcre;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\PatternBuilder;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_prepared()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->prepare(['%You/her, (are|is) ', ['real? % (or are you not real?)'], ' (you|her)%']);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, (are|is) real\?\ \%\ \(or\ are\ you\ not\ real\?\) (you|her)%uXSD', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_bind()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->bind('%You/her, (are|is) @question (you|her)%', [
            'question' => 'real? % (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, (are|is) real\?\ \%\ \(or\ are\ you\ not\ real\?\) (you|her)%uXSD', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->inject('%You/her, (are|is) @ (you|her)%', [
            'real? % (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, (are|is) real\?\ \%\ \(or\ are\ you\ not\ real\?\) (you|her)%uXSD', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_putMask_build()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->template('%You/her, & (her)%', 's')
            ->putMask('%s', ['%s' => '\s'])
            ->build();

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_putLiteral()
    {
        // given
        $pattern = PatternBuilder::builder()
            ->pcre()
            ->template('%You/her, & (her)%', 's')
            ->putLiteral('{hi}')
            ->build();

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \{hi\} (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_mask()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->template('%You/her, & (her)%', 's')->mask('%s', ['%s' => '\s']);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_inject()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->template('%You/her, \s (her)%', 's')->inject([]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_bind()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->template('%You/her, \s (her)%', 's')->bind([]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_bind_DefaultFlags()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->template('%You/her, \s (her)%')->bind([]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%', $pattern);
    }
}
