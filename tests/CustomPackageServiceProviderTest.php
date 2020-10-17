<?php

namespace Diglactic\Breadcrumbs\Tests;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator;
use Diglactic\Breadcrumbs\Manager;
use Diglactic\Breadcrumbs\ServiceProvider as BreadcrumbsServiceProvider;
use Illuminate\Support\ServiceProvider;

class CustomPackageServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            BreadcrumbsServiceProvider::class,
            CustomPackageServiceProvider::class,
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

class CustomPackageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(Manager $breadcrumbs): void
    {
        $breadcrumbs->for('home', function (Generator $trail) {
            $trail->push('Home', '/');
        });
    }
}
