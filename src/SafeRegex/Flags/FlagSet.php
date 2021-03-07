<?php
namespace TRegx\SafeRegex\Flags;

use TRegx\CleanRegex\Internal\Flags\TogglesFlags;

class FlagSet
{
    use TogglesFlags;

    /** @var string */
    private $flags;

    public function __construct(string $flags)
    {
        $this->flags = $this->preprocessFlags($flags);
    }

    private function preprocessFlags(string $flags): string
    {
        $array = \str_split($flags);
        \rsort($array);
        return \join(\array_unique($array));
    }

    protected function toggleFlag(string $flag, bool $enabled): FlagSet
    {
        if ($enabled) {
            return new FlagSet($flag . $this->flags);
        }
        return new FlagSet(\str_replace($flag, '', $this->flags));
    }

    public function __toString(): string
    {
        return $this->flags;
    }
}
