<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder\builder\mask;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet()
    {
        // given
        $builder = Pattern::builder();

        // when
        $pattern = $builder->mask('(super):{%s.%d.%%}', [
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
    public function shouldGet_WithFlags()
    {
        // given
        $patternBuilder = Pattern::builder();

        // when
        $pattern = $patternBuilder->mask('My(super)pattern', [], 'ui');

        // then
        $this->assertSame('/My\(super\)pattern/ui', $pattern->delimited());
    }
}
