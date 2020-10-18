<?php

namespace Diglactic\Breadcrumbs\Tests;

class AliasTest extends TestCase
{
    public function testCanResolveDeprecatedClasses(): void
    {
        // Verify classes referenced in legacy README resolve for backwards compatibility
        // @see https://github.com/davejamesmiller/laravel-breadcrumbs/blob/master/README.md
        foreach (
            [
                \DaveJamesMiller\Breadcrumbs\BreadcrumbsManager::class,
                \DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator::class,
                \DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider::class,
                \DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::class,
            ]
            as $deprecatedClassReferencedInREADME
        ) {
            $this->assertTrue(\class_exists($deprecatedClassReferencedInREADME));
        }
    }
}
