<?php

namespace Diglactic\Breadcrumbs\Tests;

use Diglactic\Breadcrumbs\Breadcrumbs;

class SingleFileLoadingTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        parent::defineEnvironment($app);

        $app->config->set('breadcrumbs.files', __DIR__ . '/routes/breadcrumbs.php');
    }

    public function testLoading()
    {
        $html = Breadcrumbs::render('single-file-test')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li class="current">Loaded</li>
            </ol>
        ', $html);
    }
}
