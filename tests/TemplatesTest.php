<?php

namespace Diglactic\Breadcrumbs\Tests;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;

class TemplatesTest extends TestCase
{
    private object $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Home (Normal link)
        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Home', url('/'));
        });

        // Home > Blog (Not a link)
        Breadcrumbs::for('blog', function ($trail) {
            $trail->parent('home');
            $trail->push('Blog');
        });

        // Home > Blog > [Category] (Active page)
        Breadcrumbs::for('category', function ($trail, $category) {
            $trail->parent('blog');
            $trail->push($category->title, url("blog/category/{$category->id}"));
        });

        $this->category = (object)[
            'id' => 456,
            'title' => 'Sample Category',
        ];
    }

    public static function viewProvider(): Generator
    {
        foreach (glob(__DIR__ . '/../resources/views/*.blade.php') as $filename) {
            $name = basename($filename, '.blade.php');
            yield $name => [$name];
        }
    }

    #[DataProvider('viewProvider')]
    public function testView($view)
    {
        $html = Breadcrumbs::view("breadcrumbs::{$view}", 'category', $this->category)->toHtml();

        $this->assertMatchesXmlSnapshot($html);
    }

    public function testCanResolveFacadeAbsolutely()
    {
        $this->expectNotToPerformAssertions();

        \Breadcrumbs::for('foo', function ($trail, $category) {
            $trail->parent('blog');
            $trail->push($category->title, url("blog/category/{$category->id}"));
        });
    }

    public function testCanResolveFacadeDirectly()
    {
        $this->expectNotToPerformAssertions();

        \Diglactic\Breadcrumbs\Breadcrumbs::for('bar', function (\Diglactic\Breadcrumbs\Generator $trail) {
            $trail->parent('blog');
            $trail->push('Bar', 'some/path');
        });
    }
}
