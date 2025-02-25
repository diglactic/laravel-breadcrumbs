<?php

namespace Diglactic\Breadcrumbs\Tests;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Contracts\Console\Kernel;

class DeferredLoadingTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        parent::defineEnvironment($app);

        // If the service provider is loaded before the test starts, this file
        // will throw an exception.
        $app->config->set('breadcrumbs.files', [__DIR__ . '/routes/should-not-be-loaded.php']);
    }

    protected function resolveApplicationConsoleKernel($app): void
    {
        // Disable the console kernel because it calls loadDeferredProviders()
        // which defeats the purpose of this test
        $app->singleton(Kernel::class, DisabledConsoleKernel::class);
    }

    public function testDeferredLoading()
    {
        $this->expectException(\LogicException::class);

        // This triggers the service provider boot, which loads the breadcrumbs,
        // which throws an exception, which is caught by PHPUnit.
        Breadcrumbs::clearCurrentRoute();
    }
}

class DisabledConsoleKernel extends \Orchestra\Testbench\Foundation\Console\Kernel
{
    public function bootstrap(): void
    {
        //
    }
}
