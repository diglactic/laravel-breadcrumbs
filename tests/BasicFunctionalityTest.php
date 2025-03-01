<?php

namespace Diglactic\Breadcrumbs\Tests;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Route;
use LogicException;

class BasicFunctionalityTest extends TestCase
{
    private object $post;

    protected function setUp(): void
    {
        parent::setUp();

        $closure = function () {
            throw new LogicException;
        };

        // Home
        Route::name('home')->get('/', $closure);

        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Home', route('home'));
        });

        // Home > About
        Route::name('about')->get('about', $closure);

        Breadcrumbs::for('about', function ($trail) {
            $trail->parent('home');
            $trail->push('About', route('about'));
        });

        // Home > Blog
        Route::name('blog')->get('blog', $closure);

        Breadcrumbs::for('blog', function ($trail) {
            $trail->parent('home');
            $trail->push('Blog', route('blog'));
        });

        // Home > Blog > [Category]
        Route::name('category')->get('blog/category/{category}', $closure);

        Breadcrumbs::for('category', function ($trail, $category) {
            $trail->parent('blog');
            $trail->push($category->title, route('category', $category->id));
        });

        // Home > Blog > [Category] > [Post]
        Route::name('post')->get('blog/post/{post}', $closure);

        Breadcrumbs::for('post', function ($trail, $post) {
            $trail->parent('category', $post->category);
            $trail->push($post->title, route('post', $post->id));
        });

        $this->category = (object)[
            'id' => 456,
            'title' => 'Sample Category',
        ];

        $this->post = (object)[
            'id' => 123,
            'title' => 'Sample Post',
            'category' => $this->category,
        ];
    }

    public function testGenerate()
    {
        $breadcrumbs = Breadcrumbs::generate('post', $this->post);

        $this->assertCount(4, $breadcrumbs);

        $this->assertSame('Home', $breadcrumbs[0]->title);
        $this->assertSame('http://localhost', $breadcrumbs[0]->url);

        $this->assertSame('Blog', $breadcrumbs[1]->title);
        $this->assertSame('http://localhost/blog', $breadcrumbs[1]->url);

        $this->assertSame('Sample Category', $breadcrumbs[2]->title);
        $this->assertSame('http://localhost/blog/category/456', $breadcrumbs[2]->url);

        $this->assertSame('Sample Post', $breadcrumbs[3]->title);
        $this->assertSame('http://localhost/blog/post/123', $breadcrumbs[3]->url);
    }

    public function testRenderHome()
    {
        $rendered = Breadcrumbs::render('home');
        $html = $rendered->toHtml();

        $this->assertInstanceOf(Htmlable::class, $rendered);

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li class="current">Home</li>
            </ol>
        ', $html);
    }

    public function testRenderBlog()
    {
        $html = Breadcrumbs::render('blog')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li><a href="http://localhost">Home</a></li>
                <li class="current">Blog</li>
            </ol>
        ', $html);
    }

    public function testRenderCategory()
    {
        $html = Breadcrumbs::render('category', $this->category)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li><a href="http://localhost">Home</a></li>
                <li><a href="http://localhost/blog">Blog</a></li>
                <li class="current">Sample Category</li>
            </ol>
        ', $html);
    }

    public function testRenderPost()
    {
        $html = Breadcrumbs::render('post', $this->post)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li><a href="http://localhost">Home</a></li>
                <li><a href="http://localhost/blog">Blog</a></li>
                <li><a href="http://localhost/blog/category/456">Sample Category</a></li>
                <li class="current">Sample Post</li>
            </ol>
        ', $html);
    }
}
