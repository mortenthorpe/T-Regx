<?php
namespace Test\Utils;

class Arrays
{
    public static function mapKeys(callable $function, array $elements): array
    {
        $newArray = [];
        foreach ($elements as $oldKey => $value) {
            $newArray[$function($value)] = $value;
        }
        return $newArray;
    }
}
