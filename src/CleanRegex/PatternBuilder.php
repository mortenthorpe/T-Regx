<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\CompositePatternMapper;
use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\MaskParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\Prepare;
use TRegx\CleanRegex\Internal\Prepared\Template\NoTemplate;

class PatternBuilder
{
    /** @var bool */
    private $pcre;

    private function __construct(bool $pcre)
    {
        $this->pcre = $pcre;
    }

    public static function builder(): PatternBuilder
    {
        return new self(false);
    }

    public function pcre(): PatternBuilder
    {
        return new self(true);
    }

    /**
     * @param string $input
     * @param string[]|string[][] $values
     * @param string $flags
     * @return PatternInterface
     */
    public function bind(string $input, array $values, string $flags = null): PatternInterface
    {
        return Prepare::build(new BindingParser($input, $values, new NoTemplate()), $this->pcre, $flags ?? Flags::default());
    }

    /**
     * @param string $input
     * @param string[]|string[][] $values
     * @param string $flags
     * @return PatternInterface
     */
    public function inject(string $input, array $values, string $flags = null): PatternInterface
    {
        return Prepare::build(new InjectParser($input, $values, new NoTemplate()), $this->pcre, $flags ?? Flags::default());
    }

    /**
     * @param (string|string[])[] $input
     * @param string $flags
     * @return PatternInterface
     */
    public function prepare(array $input, string $flags = null): PatternInterface
    {
        return Prepare::build(new PreparedParser($input), $this->pcre, $flags ?? Flags::default());
    }

    /**
     * @param (string|PatternInterface)[] $patterns
     * @return CompositePattern
     */
    public static function compose(array $patterns): CompositePattern
    {
        return new CompositePattern((new CompositePatternMapper($patterns))->createPatterns());
    }

    public function mask(string $mask, array $keywords, string $flags = null): PatternInterface
    {
        return Prepare::build(new MaskParser($mask, $keywords), $this->pcre, $flags ?? Flags::default());
    }

    public function template(string $pattern, string $flags = null): Template
    {
        $flagSet = $flags ?? ($this->pcre ? Flags::empty() : Flags::default());
        return new Template($pattern, $flagSet, $this->pcre);
    }
}
