<?php
namespace Test\Utils\Verification;

use JsonSerializable;

class Method implements JsonSerializable
{
    /** @var string */
    public $type;
    /** @var array */
    public $methods;
    /** @var string|null */
    public $case;
    /** @var array */
    public $details;
    /** @var array */
    public $specific;

    public function __construct(string $type, array $methods, array $cases, array $details, array $specific)
    {
        $this->type = $type;
        $this->methods = $methods;
        $this->case = $this->oneOrNull($cases);
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
        $tags = implode('_', array_filter(array_merge($this->methods, [$this->case])));
        return "should{$this->type}_$tags";
    }

    public function markup(): string
    {
        $case = $this->case !== null ? " ($this->case)" : '';
        $methods = implode('->', array_map(fn($n) => "$n()", $this->methods));
        $d = $this->details ? json_encode($this->details) : '';
        $s = $this->specific ? json_encode($this->specific) : '';
        return trim(str_pad("$methods = {$this->type}$case", 130) . $d . $s);
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'methods'  => $this->methods,
            'details'  => $this->details,
            'specific' => $this->specific,
            'case'     => $this->case,
            'type'     => $this->type,
        ]);
    }

    private function oneOrNull(array $cases)
    {
        if (count($cases) === 0) {
            return null;
        }
        if (count($cases) === 1) {
            return $cases[0];
        }
        throw new \InvalidArgumentException();
    }

    public function __toString()
    {
        return $this->stringify();
    }

}
