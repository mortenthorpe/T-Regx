<?php
namespace TRegx\CleanRegex\Internal\Flags;

use TRegx\CleanRegex\Flags;
use TRegx\SafeRegex\Flags\FlagSet;

trait ProvidesSingleFlags
{
    public static function anchoring(bool $forceAnchor = true): FlagSet
    {
        return Flags::default()->anchoring($forceAnchor);
    }

    public static function caseInsensitive(bool $insensitive = true): FlagSet
    {
        return Flags::default()->caseInsensitive($insensitive);
    }

    public static function commentsAndStructure(bool $extended = true): FlagSet
    {
        return Flags::default()->commentsAndStructure($extended);
    }

    public static function distinctDollar(bool $dollarEndOnly = true): FlagSet
    {
        return Flags::default()->distinctDollar($dollarEndOnly);
    }

    public static function dotMatchingNewline(bool $dotAll = true): FlagSet
    {
        return Flags::default()->dotMatchingNewline($dotAll);
    }

    public static function duplicateNames(bool $allow = true): FlagSet
    {
        return Flags::default()->duplicateNames($allow);
    }

    public static function escapeRestriction(bool $restrict = true): FlagSet
    {
        return Flags::default()->escapeRestriction($restrict);
    }

    public static function multiline(bool $multiline = true): FlagSet
    {
        return Flags::default()->multiline($multiline);
    }

    public static function invertedGreediness(bool $ungreedy = true): FlagSet
    {
        return Flags::default()->invertedGreediness($ungreedy);
    }

    public static function patternAnalyzing(bool $studyPattern = true): FlagSet
    {
        return Flags::default()->patternAnalyzing($studyPattern);
    }

    public static function unicode(bool $utf8 = true): FlagSet
    {
        return Flags::default()->unicode($utf8);
    }
}
