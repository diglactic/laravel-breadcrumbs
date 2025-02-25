<?php

namespace App\Http\Controllers;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Tests\Models\Post;
use Illuminate\View\View;

class PostController
{
    public function edit(Post $post): View
    {
        return Breadcrumbs::render();
    }
}
