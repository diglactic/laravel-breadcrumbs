<?php

namespace Diglactic\Breadcrumbs\Tests;

use Breadcrumbs;
use Config;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Collection;
use Route;
use URL;

class CustomManagerTest extends TestCase
{
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        // Need to inject this early, before the package is loaded, to simulate it being set in the config file
        $app['config']['breadcrumbs.manager-class'] = CustomBreadcrumbs::class;
    }

    public function testCustomManager()
    {
        $breadcrumbs = Breadcrumbs::generate();

        $this->assertSame('custom-manager', $breadcrumbs[0]);
    }
}

class CustomBreadcrumbs extends Breadcrumbs
{
    public function generate(string $name = null, ...$params): Collection
    {
        return new Collection(['custom-manager']);
    }
}
