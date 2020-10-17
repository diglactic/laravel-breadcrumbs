<?php

namespace Diglactic\Breadcrumbs\Tests;

use Breadcrumbs;
use Diglactic\Breadcrumbs\ServiceProvider as BreadcrumbsServiceProvider;

class CustomChildServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CustomChildServiceProvider::class,
        ];
    }

    public function testRender()
    {
        $html = Breadcrumbs::render('home')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li class="current">Home</li>
            </ol>
        ', $html);
    }
}

class CustomChildServiceProvider extends ServiceProvider
{
    public function registerBreadcrumbs(): void
    {
        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Home', '/');
        });
    }
}
