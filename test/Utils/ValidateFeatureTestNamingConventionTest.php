<?php
namespace Test\Utils;

use PHPUnit\Framework\TestCase;
use Test\Utils\Verification\FeatureTests;
use Test\Utils\Verification\Method;
use Test\Utils\Verification\MethodName;

class ValidateFeatureTestNamingConventionTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Test\Utils\Verification\FeatureTests::structure()
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
     * @dataProvider \Test\Utils\Verification\FeatureTests::structure()
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
        $structure = FeatureTests::methodNames();

        // when
        $duplicates = Arrays::getDuplicates($structure);

        // then
        $this->assertEquals([], $duplicates);
    }

    /**
     * @test
     */
    public function shouldMeetAllRequirements()
    {
        // given
        $all = FeatureTests::methodObjects();

        $all = array_map(fn(Method $method) => $method->markup(), $all);
        sort($all);

        $all2 = array_keys(array_flip($all));

        // then
        $a = 4;
    }

    public function groupBy($array, callable $callback, callable $callback2): array
    {
        $result = [];
        foreach ($array as $element) {
            $var = $callback2($element);
            if ($var === null) {
                $result[$callback($element)][] = $element;
            } else {
                $result[$callback($element)][$var][] = $element;
            }
        }
        return $result;
    }

    public function presentArray($array): array
    {
        $array = array_map('json_encode', $array);
        sort($array);
        return $array;
    }
}
