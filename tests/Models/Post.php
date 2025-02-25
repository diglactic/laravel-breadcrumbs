<?php

namespace Diglactic\Breadcrumbs\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function __construct($id = null)
    {
        $this->id = $id;
        $this->title = "Post $id";
    }

    // Manual loading
    public static function findOrFail($id): static
    {
        return new static($id);
    }

    // Route model binding
    public function where($column, $value): object
    {
        return new class($value) {
            public function __construct($id)
            {
                $this->id = $id;
            }

            // Explicit route model binding
            public function first(): Post
            {
                return new Post($this->id);
            }

            // Implicit route model binding
            public function firstOrFail(): Post
            {
                return new Post($this->id);
            }
        };
    }
}
