<?php
namespace Test\Utils;

class Arrays
{
    public static function map(array $elements, callable $function): array
    {
        return array_map($function, $elements);
    }

    public static function mapKeys(callable $function, array $elements): array
    {
        $newArray = [];
        foreach ($elements as $oldKey => $value) {
            $newArray[$function($value)] = $value;
        }
        return $newArray;
    }

    public static function getDuplicates(array $structure): array
    {
        return array_keys(array_filter(array_count_values(call_user_func_array('array_merge', $structure)), function (int $count) {
            return $count > 1;
        }));
    }
}
