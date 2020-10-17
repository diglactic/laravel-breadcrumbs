<?php

namespace App\Http\Controllers;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Tests\Models\Post;

class PostController
{
    public function edit(Post $post)
    {
        return Breadcrumbs::render();
    }
}
