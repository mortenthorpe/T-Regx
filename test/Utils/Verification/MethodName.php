<?php
namespace Test\Utils\Verification;

use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PatternInterface;

class MethodName
{
    public function isValid(string $methodName): bool
    {
        if ($methodName == 'shouldBe_countable') {
            return true;
        }
        if ($this->pattern()->test($methodName)) {
            return true;
        }
        if ($this->groupPattern()->test($methodName)) {
            return true;
        }
        return false;
    }

    private function pattern(): PatternInterface
    {
        return Pattern::inject('^should@_((fluent_(filter_)?)|(filter_(fluent_)?))?@', [
            $this->features(),
            $this->firstLevelMethods(),
        ]);
    }

    private function groupPattern(): PatternInterface
    {
        return Pattern::inject('^should@_group[01]?_(fluent_)?@', [
            $this->features(),
            $this->groupLevelMethods(),
        ]);
    }

    private function features(): array
    {
        return ['Return', 'Throw', 'Delegate', 'Receive', 'Pass', 'NotCall', 'Be', 'PreserveUserData'];
    }

    private function firstLevelMethods(): array
    {
        return [
            'count', 'test',
            'first', 'findFirst',
            'forEach', 'map', 'flatMap',
            'distinct', 'all', 'only1', 'only2',
            'groupByName',
            'offsets', 'asInt', 'asArray',
            'groupByCallback',
            'trio',
        ];
    }

    private function groupLevelMethods(): array
    {
        return ['first', 'findFirst', 'findFirstOrThrow', 'all', 'map', 'flatMap', 'only', 'only1', 'filter', 'offsets'];
    }
}
