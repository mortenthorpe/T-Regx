<?php
namespace Test\Utils\Verification;

use Iterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Files
{
    public static function listClasses(string $path, string $namespace): array
    {
        $iterator = self::iterator($path);
        $classes = [];
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                $classes = array_merge($classes, self::listClasses($item->getPathname(), "$namespace\\" . $item->getFilename()));
                continue;
            }
            if ($item->isFile() && $item->getExtension() === 'php') {
                $class = $namespace . '\\' . $item->getBasename('.php');
                if (class_exists($class)) {
                    $classes[] = $class;
                }
            }
        }
        return $classes;
    }

    private static function iterator(string $path): Iterator
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
    }
}
