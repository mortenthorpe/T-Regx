<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\Flags\ProvidesSingleFlags;
use TRegx\SafeRegex\Flags\FlagSet;

class Flags
{
    use ProvidesSingleFlags;

    public static function default(): FlagSet
    {
        return new FlagSet('uXSD');
    }

    public static function empty(): FlagSet
    {
        return new FlagSet('');
    }
}
