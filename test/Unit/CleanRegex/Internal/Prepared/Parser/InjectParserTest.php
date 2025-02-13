<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\Identity;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Template\NoTemplate;

class InjectParserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetDelimiterable(): void
    {
        // given
        $parser = new InjectParser('/#', [], new NoTemplate());

        // when
        $delimiterable = $parser->getDelimiterable();

        // then
        $this->assertSame('/#', $delimiterable);
    }

    /**
     * @test
     */
    public function shouldThrow_trailingSlash(): void
    {
        // given
        $parser = new InjectParser('string @\\', ['foo'], new NoTemplate());

        // then
        $this->expectException(TrailingBackslashException::class);

        // when
        $parser->parse('/', new Identity());
    }
}
