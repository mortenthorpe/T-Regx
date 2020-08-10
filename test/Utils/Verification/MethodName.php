<?php
namespace Test\Utils\Verification;

use InvalidArgumentException;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Pattern;

class MethodName
{
    public function isValid(string $methodName): bool
    {
        try {
            $this->parse($methodName);
            return true;
        } catch (InvalidArgumentException $exception) {
            return false;
        }
    }

    public function parse(string $methodName): Method
    {
        if ($methodName == 'shouldBe_countable') {
            return Method::withSpecifics('Be', [], ['countable']);
        }

        $types = ['Return', 'Throw', 'Delegate', 'Receive', 'Pass', 'NotCall', 'Be', 'PreserveUserData'];
        [$type, $suffix] = Pattern::prepare(['^should(?<type>', [$types], ')_(.*)$'])
            ->match($methodName)
            ->findFirst(function (Match $match) {
                return [$match->get('type'), $match->get(2)];
            })
            ->orElse(function (NotMatched $notMatched) {
                throw new \InvalidArgumentException($notMatched->subject());
            });

        return $this->methods($type, explode('_', $suffix));
    }

    private function methods(string $type, array $tags): Method
    {
        $methods = array_values(array_intersect($tags, $this->firstLevelMethods()));
        $cases = array_values(array_intersect($tags, $this->matchCases()));
        $details = array_values(array_intersect($tags, $this->detailsMethods()));
        $specifics = array_values(array_intersect($tags, $this->methodSpecifics()));

        $unexpected = array_diff($tags, $this->firstLevelMethods(), $this->matchCases(), $this->detailsMethods(), $this->methodSpecifics());
        if (empty($unexpected)) {
            return new Method($type, $methods, $cases, $details, $specifics);
        }
        throw new InvalidArgumentException("Unexpected feature test name's tag(s): " . join(", ", $unexpected));
    }

    private function firstLevelMethods(): array
    {
        return [
            'test',
            'count',
            'first',
            'findFirst',
            'orElse',
            'orThrow',
            'orReturn',
            'forEach',
            'map',
            'flatMap',
            'distinct',
            'all',
            'only',
            'nth',
            'group_byName',
            'fluent',
            'filter',
            'group',
            'byteOffsets', 'offsets', 'asInt', 'asArray',
            'groupByCallback',
            'tuple',
            'triple',

            # Chained
            'texts',
            'keys',

            # Meta tests - to be fixed
            'trio',
            'mixed',
            'detailsTextOffsetAll',
        ];
    }

    private function matchCases(): array
    {
        return [
            'onUnmatchedSubject',
            'forInvalidGroup',
            'forNonexistentGroup',
            'forUnmatchedGroup',
            'forEmptyGroup',
            'forInvalidInteger',
            'forEmptyString',
            'forStringUtf8',
            'forPseudoIntegerBecausePhpSucks',
        ];
    }

    private function detailsMethods(): array
    {
        return [
            'detailsText',
            'detailsLimit',
            'detailsIndex',
            'detailsOffset',
            'detailsGet',
            'detailsAll',
            'detailsToInt',
            'detailsIsInt',
            'detailsHasGroup',
            'detailsGroup',
            'detailsGroupAll',
            'detailsGroupText',
            'detailsGroupIsInt',
            'detailsGroupToInt',
            'detailsGroupNames',
            'detailsGroupOffset',
            'detailsGroupEquals',
            'detailsGroupMatched',
            'detailsGroupReplace',
            'detailsGroupTextLength',

            'detailsGroupsNames',
            'detailsGroupsTexts',
            'detailsGroupsCount',
            'detailsGroupsOffsets',
            'detailsNamedGroupsTexts',
            'detailsNamedGroupsOffsets',

            # Details' details
            'byIndex',
            'byName',

            # NotMatched - fix inconsistencies
            'NotMatched',
            'notMatchedGroupsCount',

            # Meta tests - to be fixed
            'batch',
        ];
    }

    private function methodSpecifics(): array
    {
        return [
            'customException',
            'returnArbitraryType',
            'forUnequal',
            'keepIndexes',
            'filteredOut',
            'detailsAsString',
        ];
    }
}
