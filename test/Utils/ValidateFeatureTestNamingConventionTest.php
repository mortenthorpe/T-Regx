<?php
namespace Test\Utils;

use PHPUnit\Framework\TestCase;
use Test\Utils\Verification\FeatureTests;
use Test\Utils\Verification\Method;
use Test\Utils\Verification\MethodName;
use Test\Utils\Verification\Requirements;

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
     * @dataProvider requiredMethods
     */
    public function shouldMeetAllRequirements(string $requiredMethod)
    {
        // given
        $all = Arrays::map(FeatureTests::methodObjects(), fn(Method $method) => $method->stringify());

        // when
        $tested = in_array($requiredMethod, $all);

        // then
        $this->assertTrue($tested, "Failed to assert that method $requiredMethod() is tested");
    }

    public function requiredMethods(): array
    {
        return Arrays::map(Arrays::mapKeys(Functions::identity(), Requirements::requirements()), Functions::wrapArray());
    }
}
