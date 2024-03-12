<?php

namespace Diglactic\Breadcrumbs\Tests;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Manager;
use Generator;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionType;

class FacadePhpDocTest extends TestCase
{
    public static function tags(): Generator
    {
        $code = file_get_contents(__DIR__ . '/../src/Manager.php');

        $pattern = '/
            \*
            \s+
            @(?:param|return|throws)
            \s+
            (.+?)
            \s
            .*
        /x';

        preg_match_all($pattern, $code, $matches, PREG_SET_ORDER);

        $tags = [];
        foreach ($matches as $match) {
            foreach (explode('|', $match[1]) as $class) {
                // Return the whole line too so it can be seen in the error message
                yield [$class, $match[0]];
            }
        }
    }

    /** @dataProvider tags */
    public function testFullyQualifiedClassNames($class, $line)
    {
        // IDE Helper (v2.4.3) doesn't rewrite class names to FQCNs, so make sure only
        // fully qualified class names and built-in types are used in the Manager class
        // ----------------------------------------------------------------------------
        // Note: this issue was patched in v2.9.1, which only supported Laravel 7+.
        // Once we drop support for Laravel 6, we can entirely remove this test.
        // @see https://github.com/barryvdh/laravel-ide-helper/issues/627
        $this->assertMatchesRegularExpression(
            '/^(\\\\.*|array|object|bool|callable|int|mixed|null|string|void)$/',
            $class,
            "Must use fully qualified class names in BreadcrumbsManger PhpDoc: $line"
        );
    }

    public function testBreadcrumbsFacade()
    {
        $class = new ReflectionClass(Manager::class);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        $macroableTrait = new ReflectionClass(Macroable::class);
        /** @var \Illuminate\Support\Collection $macroableMethods */
        $macroableMethods = collect($macroableTrait->getMethods(ReflectionMethod::IS_PUBLIC))->map->name;

        $facadeDocBlock = (new ReflectionClass(Breadcrumbs::class))->getDocComment();

        collect($methods)
            ->filter(function (ReflectionMethod $method) {
                // Ignore magic methods
                return !Str::startsWith($method->name, '__');
            })
            ->filter(function (ReflectionMethod $method) use ($macroableMethods) {
                // Ignore methods from the Macroable trait (use @mixin instead)
                return !$macroableMethods->contains($method->name);
            })
            ->map(function (ReflectionMethod $method) {

                $doc = '* @method static ';

                if ($returnTypeDoc = $this->returnTypeDoc($method->getReturnType())) {
                    $doc .= $returnTypeDoc . ' ';
                }

                $doc .= $method->name . '(' . $this->parametersDoc($method->getParameters()) . ')';

                return $doc;
            })
            ->each(function (string $method) use ($facadeDocBlock) {
                $this->assertStringContainsString($method, $facadeDocBlock, 'Invalid docblock on Breadcrumbs facade');
            });
    }

    private function parametersDoc($parameters = []): string
    {
        return collect($parameters)
            ->map(static function (ReflectionParameter $parameter) {
                $doc = '$' . $parameter->getName();

                if ($parameter->isVariadic()) {
                    $doc = '...' . $doc;
                }

                if ($type = $parameter->getType()) {
                    $doc = ($type->allowsNull() ? '?' : '') . $type->getName() . ' ' . $doc;
                }

                if ($parameter->isDefaultValueAvailable()) {
                    $varExport = var_export($parameter->getDefaultValue(), true);
                    $doc .= ' = ' . ($varExport !== 'NULL' ? $varExport : 'null');
                }

                return $doc;
            })
            ->implode(', ');
    }

    private function returnTypeDoc(?ReflectionType $reflectionType = null): ?string
    {
        if (!$reflectionType) {
            return '';
        }

        $doc = $reflectionType->getName();

        if (!$reflectionType->isBuiltin()) {
            $doc = Str::start($doc, '\\');
        }

        if ($reflectionType->allowsNull()) {
            $doc .= '|null';
        }

        return $doc;
    }
}
