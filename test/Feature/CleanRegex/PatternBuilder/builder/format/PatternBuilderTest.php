<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder\builder\format;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\PatternBuilder;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFormat()
    {
        // given
        $builder = PatternBuilder::builder();

        // when
        $pattern = $builder->format('(super):{%s.%d.%%}', [
            '%s' => '\s+',
            '%d' => '\d+',
            '%%' => '%'
        ]);

        // then
        $this->assertSame('/\(super\)\:\{\s+\.\d+\.%\}/uXSD', $pattern->delimited());
    }

    /**
     * @test
     */
    public function shouldFormatWithFlags()
    {
        // given
        $patternBuilder = PatternBuilder::builder();

        // when
        $pattern = $patternBuilder->format('My(super)pattern', [], 'ui');

        // then
        $this->assertSame('/My\(super\)pattern/ui', $pattern->delimited());
    }
}
