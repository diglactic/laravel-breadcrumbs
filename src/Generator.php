<?php

namespace Diglactic\Breadcrumbs;

use Diglactic\Breadcrumbs\Exceptions\InvalidBreadcrumbException;
use Illuminate\Support\Collection;

/**
 * Generate a set of breadcrumbs for a page.
 *
 * This is passed as the first parameter to all breadcrumb-generating closures. In the documentation it is named
 * `$breadcrumbs`.
 */
class Generator
{
    /** @var \Illuminate\Support\Collection Breadcrumbs currently being generated. */
    protected $breadcrumbs;

    /** @var array The registered breadcrumb-generating callbacks. */
    protected $callbacks = [];

    /**
     * Generate breadcrumbs.
     *
     * @param array $callbacks The registered breadcrumb-generating closures.
     * @param array $before The registered 'before' callbacks.
     * @param array $after The registered 'after' callbacks.
     * @param string $name The name of the current page.
     * @param array $params The parameters to pass to the closure for the current page.
     * @return \Illuminate\Support\Collection The generated breadcrumbs.
     * @throws \Diglactic\Breadcrumbs\Exceptions\InvalidBreadcrumbException if the name is (or any ancestor names are) not registered.
     */
    public function generate(array $callbacks, array $before, array $after, string $name, array $params): Collection
    {
        $this->breadcrumbs = new Collection;
        $this->callbacks = $callbacks;

        foreach ($before as $callback) {
            $callback($this);
        }

        $this->call($name, $params);

        foreach ($after as $callback) {
            $callback($this);
        }

        return $this->breadcrumbs;
    }

    /**
     * Call the closure (or class method, if registered via Manager::rule()) to generate breadcrumbs for a page.
     *
     * @param string $name The name of the page.
     * @param array $params The parameters to pass to the closure.
     * @throws \Diglactic\Breadcrumbs\Exceptions\InvalidBreadcrumbException if the name is not registered.
     */
    protected function call(string $name, array $params): void
    {
        if (!isset($this->callbacks[$name])) {
            throw new InvalidBreadcrumbException($name);
        }

        $callback = $this->callbacks[$name];

        // Registered via Manager::rule() as [class, method] - resolve the class through the container now, rather
        // than when it was registered, so it can use constructor dependency injection like a controller.
        if (is_array($callback) && is_string($callback[0])) {
            $callback = [app($callback[0]), $callback[1]];
        }

        $callback($this, ...$params);
    }

    /**
     * Add breadcrumbs for a parent page.
     *
     * Should be called from the closure for a page, before `push()` is called.
     *
     * @param string $name The name of the parent page.
     * @param mixed ...$params The parameters to pass to the closure.
     * @return self
     * @throws \Diglactic\Breadcrumbs\Exceptions\InvalidBreadcrumbException
     */
    public function parent(string $name, ...$params): self
    {
        $this->call($name, $params);

        return $this;
    }

    /**
     * Add a breadcrumb.
     *
     * Should be called from the closure for each page. May be called more than once.
     *
     * @param string $title The title of the page.
     * @param string|null $url The URL of the page.
     * @param array $data Optional associative array of additional data to pass to the view.
     * @return self
     */
    public function push(string $title, ?string $url = null, array $data = []): self
    {
        $this->breadcrumbs->push((object)array_merge($data, compact('title', 'url')));

        return $this;
    }
}
