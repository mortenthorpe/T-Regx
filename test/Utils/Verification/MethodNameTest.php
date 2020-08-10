<?php
namespace Test\Utils\Verification;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Arrays;
use Test\Utils\Functions;

class MethodNameTest extends TestCase
{
    /**
     * @test
     * @dataProvider methodNames
     */
    public function shouldParse(string $methodName, Method $expectedMethod)
    {
        // given
        $names = new MethodName();

        // when
        $method = $names->parse($methodName);

        // then
        $this->assertEquals($expectedMethod, $method, "Failed asserting that $methodName() was parsed to an expected method");
    }

    /**
     * @test
     * @dataProvider methodNames
     */
    public function shouldNotBeIgnorant(string $methodName)
    {
        // given
        $names = new MethodName();

        // when
        $method = $names->parse($methodName)->stringify();

        // then
        $this->assertEquals($methodName, $method, "Failed to assert that every element of method was taken into account");
    }

    public function methodNames(): array
    {
        return Arrays::mapKeys(Functions::fromArray(0), [
            ['shouldReceive_first', Method::create('Receive', ['first'])],
            ['shouldDelegate_fluent_filter_asInt', Method::create('Delegate', ['fluent', 'filter', 'asInt'])],
            ['shouldDelegate_group_filter_asInt', Method::create('Delegate', ['group', 'filter', 'asInt'])],
            [
                'shouldThrow_group_findFirst_orThrow_onUnmatchedSubject_customException',
                Method::withCasesSpecifics('Throw', ['group', 'findFirst','orThrow'], ['onUnmatchedSubject'], ['customException'])
            ],
            [
                'shouldReceive_group_findFirst_orThrow_detailsText_forEmptyGroup',
                Method::detailed('Receive', ['group', 'findFirst','orThrow'], ['forEmptyGroup'], ['detailsText'])
            ],
            [
                'shouldReceive_group_findFirst_orThrow_detailsText',
                Method::withDetails('Receive', ['group', 'findFirst','orThrow'], ['detailsText'])
            ],
            [
                'shouldThrow_group_findFirst_orThrow_forNonexistentGroup',
                Method::create('Throw', ['group', 'findFirst','orReturn'], ['forNonexistentGroup'])
            ],
            [
                'shouldPass_first_returnArbitraryType',
                Method::withSpecifics('Pass', ['first'], ['returnArbitraryType'])
            ],
            [
                'shouldDelegate_fluent_filter_map_nth',
                Method::create('Delegate', ['fluent', 'filter', 'map', 'nth'])
            ],
            [
                'shouldReceive_findFirst_detailsLimit',
                Method::withDetails('Receive', ['findFirst'], ['detailsLimit'])
            ],
            [
                'shouldThrow_first_detailsHasGroup_forInvalidGroup',
                Method::detailed('Throw', ['first'], ['forInvalidGroup'], ['detailsHasGroup'])
            ],
            [
                'shouldReceive_first_detailsGroupsNames_batch',
                Method::withDetails('Receive', ['first'], ['detailsGroupsNames', 'batch'])
            ],
            [
                'shouldThrow_first_forPseudoIntegerBecausePhpSucks',
                Method::create('Throw', ['first'], ['forPseudoIntegerBecausePhpSucks'])
            ],
        ]);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidMethod()
    {
        // given
        $names = new MethodName();

        // then
        $this->expectException(InvalidArgumentException::class);

        // when
        $names->parse('');
    }
}
