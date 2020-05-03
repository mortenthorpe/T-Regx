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
    public function shouldMethodNamesConformToNamingConvention(array $methods): void
    {
        // given
        foreach ($methods as $method) {
            // when
            $isValid = (new MethodName())->isValid($method);

            // then
            $this->assertTrue($isValid, "Failed asserting that method $method() conforms to naming convention.");
        }
    }

    /**
     * @test
     * @dataProvider structure
     */
    public function shouldMethodNamesBeTransitive(array $methods): void
    {
        // given
        foreach ($methods as $methodName) {
            // given
            $method = (new MethodName())->parse($methodName);

            // when
            $name = $method->stringify();

            // then
            $this->assertEquals($name, $methodName);
        }
    }

    /**
     * @test
     */
    public function shouldNotTestTheSameFeatureTwice()
    {
        // given
        $structure = $this->methods();

        // when
        $duplicates = $this->getDuplicates($structure);

        // then
        $this->assertEquals([], $duplicates);
    }

    public function methods(): array
    {
        return (new Methods())->groupMethodsByClass(
            MatchPatternTest::class,
            'Test\Feature\TRegx\CleanRegex\\'
        );
    }

    public function structure(): array
    {
        return array_map(function (array $methods) {
            return [$methods];
        }, $this->methods());
    }

    private function getDuplicates(array $structure): array
    {
        return array_keys(array_filter(array_count_values(call_user_func_array('array_merge', $structure)), function (int $count) {
            return $count > 1;
        }));
    }

    private function parseMethods(array $methods): array
    {
        $result = [];
        foreach ($methods as $files) {
            foreach ($files as $methodName) {
                // when
                $result[] = (new MethodName())->parse($methodName);
            }
        }
        return $result;
    }
}
