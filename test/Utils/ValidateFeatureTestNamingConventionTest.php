<?php
namespace Test\Utils;

use PHPUnit\Framework\TestCase;
use Test\Feature\TRegx\CleanRegex\Match\MatchPatternTest;
use Test\Utils\Verification\MethodName;
use Test\Utils\Verification\Methods;

class ValidateFeatureTestNamingConventionTest extends TestCase
{
    /**
     * @test
     * @dataProvider structure
     */
    public function test(array $methods): void
    {
        // given
        foreach ($methods as $method) {
            // when
            $isValid = (new MethodName())->isValid($method);

            // then
            $this->assertTrue($isValid, "Failed asserting that method $method() conforms to naming convention.");
        }
    }

    public function structure(): array
    {
        return (new Methods())->groupMethodsByClass(
            MatchPatternTest::class,
            'Test\Feature\TRegx\CleanRegex\\'
        );
    }
}
