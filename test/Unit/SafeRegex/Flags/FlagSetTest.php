<?php
namespace Test\Unit\TRegx\SafeRegex\Flags;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Flags\FlagSet;

class FlagSetTest extends TestCase
{
    /**
     * @test
     * @dataProvider enableFlagMethods
     * @param string $method
     * @param string $expected
     * @param string $expectedMany
     */
    public function shouldAddFlag(string $method, string $expected, string $expectedMany)
    {
        // given
        $empty = new FlagSet('');
        $some = new FlagSet('re');

        // when
        $single = $empty->$method();
        $many = $some->$method();

        // then
        $this->assertFlags($expected, $single);
        $this->assertFlags($expectedMany, $many);
        $this->assertFlags('', $empty);
    }

    public function enableFlagMethods(): array
    {
        return [
            ['unicode', 'u', 'ure'],
            ['anchoring', 'A', 'reA'],
            ['multiline', 'm', 'rme'],
            ['duplicateNames', 'J', 'reJ'],
            ['distinctDollar', 'D', 'reD'],
            ['caseInsensitive', 'i', 'rie'],
            ['invertedGreediness', 'U', 'reU'],
            ['dotMatchingNewline', 's', 'sre'],
            ['commentsAndStructure', 'x', 'xre'],
            ['patternAnalyzing', 'S', 'reS'],
            ['escapeRestriction', 'X', 'reX'],
        ];
    }

    /**
     * @test
     * @dataProvider toggleFlagMethods
     * @param string $method
     * @param string $expected
     */
    public function shouldToggleFlag(string $method, string $flag, string $expected)
    {
        // given
        $empty = new FlagSet('');
        $full = new FlagSet('xusmiXUSJDA');

        // when
        $enabled = $empty->$method(true);
        $disabled = $full->$method(false);

        // then
        $this->assertFlags('', $empty);
        $this->assertFlags('xusmiXUSJDA', $full);
        $this->assertFlags($flag, $enabled);
        $this->assertFlags($expected, $disabled);
    }

    public function toggleFlagMethods(): array
    {
        return [
            ['unicode', 'u', 'xsmiXUSJDA'],
            ['anchoring', 'A', 'xusmiXUSJD'],
            ['multiline', 'm', 'xusiXUSJDA'],
            ['duplicateNames', 'J', 'xusmiXUSDA'],
            ['distinctDollar', 'D', 'xusmiXUSJA'],
            ['caseInsensitive', 'i', 'xusmXUSJDA'],
            ['patternAnalyzing', 'S', 'xusmiXUJDA'],
            ['escapeRestriction', 'X', 'xusmiUSJDA'],
            ['invertedGreediness', 'U', 'xusmiXSJDA'],
            ['dotMatchingNewline', 's', 'xumiXUSJDA'],
            ['commentsAndStructure', 'x', 'usmiXUSJDA'],
        ];
    }

    /**
     * @test
     */
    public function shouldJoinFlags()
    {
        // given
        $flagSet = new FlagSet('');

        // when
        $flags = $flagSet
            ->unicode()
            ->caseInsensitive()
            ->multiline()
            ->distinctDollar()
            ->anchoring()
            ->duplicateNames()
            ->commentsAndStructure()
            ->invertedGreediness()
            ->dotMatchingNewline()
            ->patternAnalyzing()
            ->escapeRestriction();

        // then
        $this->assertFlags('xusmiXUSJDA', $flags);
    }

    /**
     * @test
     */
    public function shouldNotStoreDuplicateFlags()
    {
        // given
        $flagSet = new FlagSet('');

        // when
        $flags = $flagSet
            ->unicode()
            ->unicode()
            ->unicode()
            ->unicode()
            ->caseInsensitive()
            ->caseInsensitive()
            ->invertedGreediness();

        // then
        $this->assertFlags('uiU', $flags);
    }

    private function assertFlags(string $expected, FlagSet $actual)
    {
        $this->assertSame($expected, "$actual");
    }
}
