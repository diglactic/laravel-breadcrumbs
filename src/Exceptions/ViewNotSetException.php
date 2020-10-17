<?php

namespace Diglactic\BreadcrumbsExceptions;

use Diglactic\BreadcrumbsBreadcrumbsException;
use Facade\IgnitionContracts\Solution;
use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;

/**
 * Exception that is thrown if the user attempts to render breadcrumbs without setting a view.
 */
class ViewNotSetException extends BreadcrumbsException implements ProvidesSolution
{
    public function getSolution(): Solution
    {
        $links = [];
        $links['Choosing a breadcrumbs template (view)'] = 'https://github.com/diglactic/laravel-breadcrumbs#3-choose-a-template';
        $links['Laravel Breadcrumbs documentation'] = 'https://github.com/diglactic/laravel-breadcrumbs#laravel-breadcrumbs';

        return BaseSolution::create('Set a view for Laravel Breadcrumbs')
            ->setSolutionDescription("Please check `config/breadcrumbs.php` for a valid `'view'` (e.g. `'breadcrumbs::bootstrap4'`)")
            ->setDocumentationLinks($links);
    }
}
