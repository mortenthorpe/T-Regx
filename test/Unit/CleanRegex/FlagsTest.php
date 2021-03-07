<?php
namespace Test\Unit\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Flags;
use TRegx\SafeRegex\Flags\FlagSet;

class FlagsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_empty()
    {
        // then
        $this->assertFlags('', Flags::empty());
    }

    /**
     * @test
     */
    public function shouldGet_default()
    {
        // then
        $this->assertFlags('uXSD', Flags::default());
    }

    /**
     * @test
     * @dataProvider singleFlagMethods
     * @param string $flag
     * @param string $method
     */
    public function shouldEnableSingleFlag(string $flag, string $method)
    {
        // then
        $this->assertFlags($flag, Flags::$method());
        $this->assertFlags($flag, Flags::$method(true));
    }

    public function singleFlagMethods(): array
    {
        return [
            'u' => ['uXSD', 'unicode'],
            'D' => ['uXSD', 'distinctDollar'],
            'X' => ['uXSD', 'escapeRestriction'],
            'S' => ['uXSD', 'patternAnalyzing'],
            'A' => ['uXSDA', 'anchoring'],
            'm' => ['umXSD', 'multiline'],
            'i' => ['uiXSD', 'caseInsensitive'],
            'x' => ['xuXSD', 'commentsAndStructure'],
            'U' => ['uXUSD', 'invertedGreediness'],
            'J' => ['uXSJD', 'duplicateNames'],
            's' => ['usXSD', 'dotMatchingNewline'],
        ];
    }

    /**
     * @test
     * @dataProvider singleDisablingFlags
     * @param string $flag
     * @param string $method
     */
    public function shouldDisableSingleFlag(string $flag, string $method)
    {
        // then
        $this->assertFlags($flag, Flags::$method(false));
    }

    public function singleDisablingFlags(): array
    {
        return [
            'u' => ['XSD', 'unicode'],
            'D' => ['uXS', 'distinctDollar'],
            'X' => ['uSD', 'escapeRestriction'],
            'S' => ['uXD', 'patternAnalyzing'],
            'A' => ['uXSD', 'anchoring'],
            'm' => ['uXSD', 'multiline'],
            'i' => ['uXSD', 'caseInsensitive'],
            'x' => ['uXSD', 'commentsAndStructure'],
            'U' => ['uXSD', 'invertedGreediness'],
            'J' => ['uXSD', 'duplicateNames'],
            's' => ['uXSD', 'dotMatchingNewline'],
        ];
    }

    private function assertFlags(string $expected, FlagSet $actual)
    {
        $this->assertSame($expected, "$actual");
    }
}
