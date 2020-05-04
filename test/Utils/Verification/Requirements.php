<?php
namespace Test\Utils\Verification;

class Requirements
{
    public function userData(): array
    {
        return [
            "shouldPreserveUserData_first",
            "shouldPreserveUserData_map",
            "shouldPreserveUserData_findFirst",

            "shouldPreserveUserData_filter_fluent_first",
            "shouldPreserveUserData_filter_fluent_forEach",
            "shouldPreserveUserData_fluent_filter_forEach",

            "shouldPreserveUserData_filter_groupByName_mixed",
        ];
    }
}
