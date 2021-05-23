<?php

namespace Diglactic\Breadcrumbs\Tests;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator;
use Illuminate\Support\Collection;

class CustomGeneratorTest extends TestCase
{
    protected function resolveApplicationConfiguration($app): void
    {
        parent::resolveApplicationConfiguration($app);

        // Need to inject this early, before the package is loaded, to simulate it being set in the config file
        $app['config']['breadcrumbs.generator-class'] = CustomGenerator::class;
    }

    public function testCustomGenerator()
    {
        $breadcrumbs = Breadcrumbs::generate();

        $this->assertSame('custom-generator', $breadcrumbs[0]);
    }
}

class CustomGenerator extends Generator
{
    public function generate(array $callbacks, array $before, array $after, string $name, array $params): Collection
    {
        return new Collection(['custom-generator']);
    }

}
