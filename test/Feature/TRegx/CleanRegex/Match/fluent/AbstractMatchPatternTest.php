<?php
namespace Test\Feature\TRegx\CleanRegex\Match\fluent;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Match;

class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegate_fluent_filter_map_map_all()
    {
        // when
        $result = pattern("(?<capital>[A-Z])?[\w']+")
            ->match("I'm rather old, He likes Apples")
            ->fluent()
            ->filter(function (Match $match) {
                return $match->textLength() !== 3;
            })
            ->map(function (Match $match) {
                return $match->group('capital');
            })
            ->map(function (MatchGroup $matchGroup) {
                if ($matchGroup->matched()) {
                    return "yes: $matchGroup";
                }
                return "no";
            })
            ->all();

        // then
        $this->assertEquals(['no', 'yes: H', 'no', 'yes: A'], $result);
    }

    /**
     * @test
     */
    public function shouldDelegate_fluent_filter_map_nth()
    {
        // when
        $result = pattern("\w+")
            ->match("Lorem ipsum dolor emet")
            ->fluent()
            ->filter(function (Match $match) {
                return !in_array($match->text(), ['Lorem', 'ipsum']);
            })
            ->map(function (Match $match) {
                return $match->text();
            })
            ->nth(1);

        // then
        $this->assertEquals('emet', $result);
    }

    /**
     * @test
     */
    public function shouldPreserveUserData_fluent_filter_forEach()
    {
        // given
        pattern("\w+")
            ->match("Foo, Bar")
            ->fluent()
            ->filter(function (Match $match) {
                // when
                $match->setUserData($match === 'Foo' ? 'hey' : 'hello');

                return true;
            })
            ->forEach(function (Match $match) {
                // then
                $userData = $match->getUserData();

                $this->assertEquals($match === 'Foo' ? 'hey' : 'hello', $userData);
            });
    }

    /**
     * @test
     */
    public function shouldDelegate_fluent_filter_findFirst()
    {
        // when
        pattern("(?<capital>[A-Z])?[\w']+")
            ->match("I'm rather old, He likes Apples")
            ->fluent()
            ->filter(function (Match $match) {
                return $match->textLength() !== 3;
            })
            ->findFirst(function (Match $match) {
                $this->assertTrue(true);
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_fluent_findFirstOrThrow_onUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed is empty");

        // when
        pattern("Foo")
            ->match("Bar")
            ->fluent()
            ->findFirst(Functions::fail())
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_fluent_findFirstOrThrow_onUnmatchedSubject_customException()
    {
        try {
            // when
            pattern("Foo")
                ->match("Bar")
                ->fluent()
                ->findFirst(Functions::fail())
                ->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertEquals("Expected to get the first element from fluent pattern, but the elements feed is empty.", $exception->getMessage());
            $this->assertEquals("Bar", $exception->subject);
        }
    }

    /**
     * @test
     */
    public function shouldThrow_fluent_first_onUnmatchedSubject()
    {
        try {
            // when
            pattern("Foo")->match("Bar")->fluent()->first();
        } catch (SubjectNotMatchedException $exception) {
            // then
            $this->assertEquals("Expected to get the first element from fluent pattern, but the elements feed is empty.", $exception->getMessage());
        }
    }
}
