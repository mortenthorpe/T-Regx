<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\Format\LiteralTokenValue;
use TRegx\CleanRegex\Internal\Prepared\Format\MaskTokenValue;

class TemplatePattern
{
    /** @var string */
    private $pattern;
    /** @var bool */
    private $pcre;
    /** @var string */
    private $flags;

    public function __construct(string $pattern, string $flags, bool $pcre)
    {
        $this->pattern = $pattern;
        $this->pcre = $pcre;
        $this->flags = $flags;
    }

    public function putMask(string $mask, array $keywords): FormatTemplate
    {
        return new FormatTemplate($this->pattern, $this->flags, $this->pcre, [new MaskTokenValue($mask, $keywords)]);
    }

    public function putLiteral(): FormatTemplate
    {
        return new FormatTemplate($this->pattern, $this->flags, $this->pcre, [new LiteralTokenValue()]);
    }

    public function mask(string $string, array $tokens): PatternInterface
    {
        $template = new FormatTemplate($this->pattern, $this->flags, $this->pcre, [new MaskTokenValue($string, $tokens)]);
        return $template->build();
    }

    public function inject(array $values): PatternInterface
    {
        $template = new FormatTemplate($this->pattern, $this->flags, $this->pcre, []);
        return $template->inject($values);
    }

    public function bind(array $values): PatternInterface
    {
        $template = new FormatTemplate($this->pattern, $this->flags, $this->pcre, []);
        return $template->bind($values);
    }
}
