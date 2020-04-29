<?php
namespace Test\Utils\Verification;

class Strings
{
    public static function unPrepend(string $text, string $prefix): string
    {
        if (self::startsWith($text, $prefix)) {
            return mb_substr($text, mb_strlen($prefix));
        }
        throw new \InvalidArgumentException("String doesn't start with the prefix");
    }

    public static function startsWith(string $text, string $prefix): bool
    {
        return mb_substr($text, 0, mb_strlen($prefix)) === $prefix;
    }
}
