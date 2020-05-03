<?php
namespace Test\Utils\Verification;

use ReflectionMethod;

class Methods
{
    public function groupMethodsByClass(string $reference, string $unPrependNamespace): array
    {
        $result = [];
        foreach ($this->classesByReference($reference) as $class) {
            $dataSet = Strings::unPrepend($class, $unPrependNamespace);
            if (!Strings::startsWith($dataSet, 'Match\_stream_first\\')) {
                // _stream_first aren't really feature tests. I mean they are, but not testing logic
                // they test the preg_match()/preg_match_all() need for `first()`. So it shouldn't be included here.
                $result[$dataSet] = $this->methods($class);
            }
        }
        return $result;
    }

    private function classesByReference(string $referenceClass): array
    {
        $reflection = new \ReflectionClass($referenceClass);
        return Files::listClasses(dirname($reflection->getFileName()), $reflection->getNamespaceName());
    }

    private function methods(string $class): array
    {
        $methods = [];
        $reflection = new \ReflectionClass($class);
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getDeclaringClass()->getName() != $reflection->getName()) {
                continue;
            }
            if ($this->getReturnTypeName($method) === 'array') {
                continue;
            }
            $methods[] = $method->getName();
        }
        return $methods;
    }

    private function getReturnTypeName(ReflectionMethod $method): ?string
    {
        $returnType = $method->getReturnType();
        if (!$returnType) {
            return null;
        }
        if (method_exists($returnType, 'getName')) {
            return $returnType->getName();
        }
        return (string)$returnType;
    }
}
