<?php

declare(strict_types=1);

use Rector\Architecture\Rector\Class_\MoveRepositoryFromParentToConstructorRector;
use Rector\Architecture\Rector\MethodCall\ReplaceParentRepositoryCallsByRepositoryPropertyRector;
use Rector\Core\Configuration\Option;
use Rector\Doctrine\Rector\Class_\RemoveRepositoryFromEntityAnnotationRector;
use Rector\Set\ValueObject\SetList;
use Rector\SOLID\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\TypeDeclaration\Rector\Property\PropertyTypeDeclarationRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FinalizeClassesWithoutChildrenRector::class);
    $services->set(PropertyTypeDeclarationRector::class);
    $services->set(MoveRepositoryFromParentToConstructorRector::class);

    // no Symfony generated mess magic in repositories
    $services->set(ReplaceParentRepositoryCallsByRepositoryPropertyRector::class);
    $services->set(MoveRepositoryFromParentToConstructorRector::class);
    $services->set(RemoveRepositoryFromEntityAnnotationRector::class);

    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
    ]);

    $parameters->set(Option::SETS, [SetList::CODE_QUALITY, SetList::NAMING]);

};
