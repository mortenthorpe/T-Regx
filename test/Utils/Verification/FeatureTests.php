<?php
namespace Test\Utils\Verification;

use Test\Feature\TRegx\CleanRegex\Match\MatchPatternTest;
use Test\Utils\Functions;

class FeatureTests
{
    public static function structure(): array
    {
        return array_map(Functions::wrapArray(), self::methodNames());
    }

    public static function methodNames(): array
    {
        return (new Methods())->groupMethodsByClass(
            MatchPatternTest::class,
            'Test\Feature\TRegx\CleanRegex\\'
        );
    }

    public static function methodObjects(): array
    {
        $result = [];
        foreach (self::methodNames() as $files) {
            foreach ($files as $methodName) {
                $result[] = (new MethodName())->parse($methodName);
            }
        }
        return $result;
    }
}
