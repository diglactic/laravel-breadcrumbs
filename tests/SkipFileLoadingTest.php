<?php

namespace Diglactic\Breadcrumbs\Tests;

class SkipFileLoadingTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        parent::defineEnvironment($app);

        $app->config->set('breadcrumbs.files', []);
    }

    public function testLoading()
    {
        // I can't think of a way to actually test this since nothing is loaded -
        // see code coverage (if (!$files) { return; })
        $this->assertTrue(true);
    }
}
