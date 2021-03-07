<?php
namespace TRegx\CleanRegex\Internal\Flags;

use TRegx\Flag;
use TRegx\SafeRegex\Flags\FlagSet;

trait TogglesFlags
{
    protected abstract function toggleFlag(string $flag, bool $enabled): FlagSet;

    public function anchoring(bool $forceAnchor = true): FlagSet
    {
        return $this->toggleFlag(Flag::ANCHORED, $forceAnchor);
    }

    public function caseInsensitive(bool $insensitive = true): FlagSet
    {
        return $this->toggleFlag(Flag::CASE_INSENSITIVE, $insensitive);
    }

    public function commentsAndStructure(bool $extended = true): FlagSet
    {
        return $this->toggleFlag(Flag::EXTENDED, $extended);
    }

    public function distinctDollar(bool $dollarEndOnly = true): FlagSet
    {
        return $this->toggleFlag(Flag::DOLLAR_END_ONLY, $dollarEndOnly);
    }

    public function dotMatchingNewline(bool $dotAll = true): FlagSet
    {
        return $this->toggleFlag(Flag::DOT_ALL, $dotAll);
    }

    public function duplicateNames(bool $allow = true): FlagSet
    {
        return $this->toggleFlag(Flag::DUMP_NAMES, $allow);
    }

    public function escapeRestriction(bool $restrict = true): FlagSet
    {
        return $this->toggleFlag(Flag::EXTRA, $restrict);
    }

    public function invertedGreediness(bool $ungreedy = true): FlagSet
    {
        return $this->toggleFlag(Flag::UNGREEDY, $ungreedy);
    }

    public function multiline(bool $multiline = true): FlagSet
    {
        return $this->toggleFlag(Flag::MULTILINE, $multiline);
    }

    public function patternAnalyzing(bool $studyPattern = true): FlagSet
    {
        return $this->toggleFlag(Flag::STUDY, $studyPattern);
    }

    public function unicode(bool $utf8 = true): FlagSet
    {
        return $this->toggleFlag(Flag::UTF8, $utf8);
    }
}
