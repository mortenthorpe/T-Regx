<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder\builder\mask;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class PatternBuilderTest extends TestCase
{
    use AssertsPattern;

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
        $this->assertSamePattern('/\(super\)\:\{\s+\.\d+\.%\}/uXSD', $pattern);
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
        $this->assertSamePattern('/My\(super\)pattern/ui', $pattern);
    }
}
