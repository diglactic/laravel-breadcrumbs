<?php

namespace Diglactic\Breadcrumbs\Tests;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Exceptions\DuplicateBreadcrumbException;
use Diglactic\Breadcrumbs\Generator;
use Illuminate\Http\Request;

class ClassMethodRuleTest extends TestCase
{
    public function testInvokableClassDefaultsToInvokeMethod()
    {
        Breadcrumbs::rule('home', InvokableHomeBreadcrumb::class);

        $html = Breadcrumbs::render('home')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li class="current">Home</li>
            </ol>
        ', $html);
    }

    public function testCustomMethodName()
    {
        Breadcrumbs::rule('about', AboutBreadcrumb::class, 'show');

        $html = Breadcrumbs::render('about')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li class="current">About</li>
            </ol>
        ', $html);
    }

    public function testConstructorDependencyInjection()
    {
        Breadcrumbs::rule('injected', InjectedBreadcrumb::class);

        $html = Breadcrumbs::render('injected')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li class="current">Injected OK</li>
            </ol>
        ', $html);
    }

    public function testParamsArePassedToTheMethod()
    {
        Breadcrumbs::rule('post', PostBreadcrumb::class);

        $post = (object)['title' => 'Sample Post'];

        $html = Breadcrumbs::render('post', $post)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li class="current">Sample Post</li>
            </ol>
        ', $html);
    }

    public function testDuplicateRuleThrowsException()
    {
        $this->expectException(DuplicateBreadcrumbException::class);

        Breadcrumbs::rule('duplicate-rule', InvokableHomeBreadcrumb::class);
        Breadcrumbs::rule('duplicate-rule', InvokableHomeBreadcrumb::class);
    }

    public function testClassIsNotInstantiatedUntilGenerated()
    {
        Breadcrumbs::rule('never-called', NeverInstantiatedBreadcrumb::class);

        $this->assertSame(0, NeverInstantiatedBreadcrumb::$instantiations);
    }
}

class InvokableHomeBreadcrumb
{
    public function __invoke(Generator $trail): void
    {
        $trail->push('Home');
    }
}

class AboutBreadcrumb
{
    public function show(Generator $trail): void
    {
        $trail->push('About');
    }
}

class InjectedBreadcrumb
{
    public function __construct(private Request $request)
    {
    }

    public function __invoke(Generator $trail): void
    {
        $trail->push($this->request instanceof Request ? 'Injected OK' : 'Injected FAIL');
    }
}

class PostBreadcrumb
{
    public function __invoke(Generator $trail, object $post): void
    {
        $trail->push($post->title);
    }
}

class NeverInstantiatedBreadcrumb
{
    public static int $instantiations = 0;

    public function __construct()
    {
        static::$instantiations++;
    }

    public function __invoke(Generator $trail): void
    {
        $trail->push('Never');
    }
}
