<?php

namespace DaveJamesMiller\Breadcrumbs;

class_alias(\Diglactic\Breadcrumbs\Manager::class, BreadcrumbsManager::class);
class_alias(\Diglactic\Breadcrumbs\Generator::class, BreadcrumbsGenerator::class);

if (!\class_exists(BreadcrumbsGenerator::class)) {
    /** @deprecated */
    class BreadcrumbsGenerator {}
}

if (!\class_exists(BreadcrumbsManager::class)) {
    /** @deprecated */
    class BreadcrumbsManager {}
}
