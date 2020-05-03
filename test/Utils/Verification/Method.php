<?php
namespace Test\Utils\Verification;

use JsonSerializable;

class Method implements JsonSerializable
{
    /** @var string */
    public $type;
    /** @var array */
    public $methods;
    /** @var array */
    public $cases;
    /** @var array */
    public $details;
    /** @var array */
    public $specific;

    public function __construct(string $type, array $methods, array $cases, array $details, array $specific)
    {
        $this->type = $type;
        $this->methods = $methods;
        $this->cases = $cases;
        $this->details = $details;
        $this->specific = $specific;
    }

    public static function detailed(string $type, array $methods, array $cases, array $details): Method
    {
        return new Method($type, $methods, $cases, $details, []);
    }

    public static function withCasesSpecifics(string $type, array $methods, array $cases, array $specifics): Method
    {
        return new Method($type, $methods, $cases, [], $specifics);
    }

    public static function withSpecifics(string $type, array $methods, array $specifics): Method
    {
        return new Method($type, $methods, [], [], $specifics);
    }

    public static function create(string $type, array $methods, array $cases = []): Method
    {
        return new Method($type, $methods, $cases, [], []);
    }

    public static function withDetails(string $type, array $methods, array $details): Method
    {
        return new Method($type, $methods, [], $details, []);
    }

    public function stringify(): string
    {
        $tags = implode('_', array_filter(array_merge($this->methods, $this->details, $this->cases, $this->specific)));
        return "should{$this->type}_$tags";
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'methods'  => $this->methods,
            'details'  => $this->details,
            'specific' => $this->specific,
            'cases'    => $this->cases,
            'type'     => $this->type,
        ]);
    }
}
