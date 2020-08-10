<?php
namespace Test\Utils\Verification;

use Test\Utils\Arrays;

class Requirements
{
    public static function requirements(): array
    {
        return array_merge(
            self::throwFirst_onSubjectNotMatched(),
            self::userData()
        );
    }

    private static function throwFirst_onSubjectNotMatched(): array
    {
        $calls = [
            '',
            'asInt_',
            'asArray_',
            'offsets_',
            'fluent_',
            'group_',
        ];

        return Arrays::map($calls, function (string $call) {
            return "shouldThrow_${call}first_onUnmatchedSubject";
        });
    }

    private static function userData(): array
    {
        return [
            // filter() could be any call before the method, able to set user data
            "shouldPreserveUserData_filter_first",
            "shouldPreserveUserData_filter_map",
            "shouldPreserveUserData_filter_findFirst",
            "shouldPreserveUserData_filter_groupByName_mixed",

            // Triple userData pass, with fluent()
            "shouldPreserveUserData_filter_fluent_first",
            "shouldPreserveUserData_filter_fluent_forEach",
            "shouldPreserveUserData_fluent_filter_forEach",
        ];
    }
}
