<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

interface DelimiterStrategy
{
    public function buildPattern(string $delimiterable, Quotable $pattern): string;

    public function getAlternationFactory(): AlterationFactory;
}
