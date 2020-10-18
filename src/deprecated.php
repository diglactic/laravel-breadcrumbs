<?php

namespace Diglactic\Breadcrumbs;

use Diglactic\Breadcrumbs\Exceptions\BaseException;
use Diglactic\Breadcrumbs\Exceptions\DuplicateBreadcrumbException;
use Diglactic\Breadcrumbs\Exceptions\InvalidBreadcrumbException;
use Diglactic\Breadcrumbs\Exceptions\UnnamedRouteException;
use Diglactic\Breadcrumbs\Exceptions\ViewNotSetException;

\class_alias(BaseException::class, \DaveJamesMiller\Breadcrumbs\BreadcrumbsException::class);
\class_alias(Breadcrumbs::class, \DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::class);
\class_alias(DuplicateBreadcrumbException::class, \DaveJamesMiller\Breadcrumbs\Exceptions\DuplicateBreadcrumbException::class);
\class_alias(Generator::class, \DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator::class);
\class_alias(InvalidBreadcrumbException::class, \DaveJamesMiller\Breadcrumbs\Exceptions\InvalidBreadcrumbException::class);
\class_alias(Manager::class, \DaveJamesMiller\Breadcrumbs\BreadcrumbsManager::class);
\class_alias(ServiceProvider::class, \DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider::class);
\class_alias(UnnamedRouteException::class, \DaveJamesMiller\Breadcrumbs\Exceptions\UnnamedRouteException::class);
\class_alias(ViewNotSetException::class, \DaveJamesMiller\Breadcrumbs\Exceptions\ViewNotSetException::class);

//

namespace DaveJamesMiller\Breadcrumbs;

if (!\class_exists(BreadcrumbsException::class)) {
    /** @deprecated */
    class BreadcrumbsException {}
}
if (!\class_exists(BreadcrumbsGenerator::class)) {
    /** @deprecated */
    class BreadcrumbsGenerator {}
}
if (!\class_exists(BreadcrumbsManager::class)) {
    /** @deprecated */
    class BreadcrumbsManager {}
}
if (!\class_exists(BreadcrumbsServiceProvider::class)) {
    /** @deprecated */
    class BreadcrumbsServiceProvider {}
}

//

namespace DaveJamesMiller\Breadcrumbs\Facades;

if (!\class_exists(Breadcrumbs::class)) {
    /** @deprecated */
    class Breadcrumbs {}
}

//

namespace DaveJamesMiller\Breadcrumbs\Exceptions;

if (!\class_exists(DuplicateBreadcrumbException::class)) {
    /** @deprecated */
    class DuplicateBreadcrumbException {}
}
if (!\class_exists(InvalidBreadcrumbException::class)) {
    /** @deprecated */
    class InvalidBreadcrumbException {}
}
if (!\class_exists(UnnamedRouteException::class)) {
    /** @deprecated */
    class UnnamedRouteException {}
}
if (!\class_exists(ViewNotSetException::class)) {
    /** @deprecated */
    class ViewNotSetException {}
}
