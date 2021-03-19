<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Format\MaskTokenValue;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class MaskParser implements Parser
{
    /** @var string */
    private $mask;
    /** @var array */
    private $keywords;

    public function __construct(string $mask, array $keywords)
    {
        $this->mask = $mask;
        $this->keywords = $keywords;
    }

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quotable
    {
        return (new MaskTokenValue($this->mask, $this->keywords))->formatAsQuotable();
    }

    public function getDelimiterable(): string
    {
        return \implode($this->keywords);
    }
}